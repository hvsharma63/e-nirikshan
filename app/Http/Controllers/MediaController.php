<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Queries\TemporaryUploadQueries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function __construct(
        private TemporaryUploadQueries $temporaryUploadQueries,
    ) {
    }

    public function uploadTemporarily(Request $request)
    {
        $purpose = $request->input('purpose');

        $rules = match ($purpose) {
            'deficiency_photo'    => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
            default               => 'required|file|max:2048',
        };

        $request->validate([
            'file' => $rules,
            'purpose' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $temporaryUploadRecord = $this->temporaryUploadQueries->firstOrCreate(
                Auth::id(),
                $request->input('uuid')
            );

            $media = $this->temporaryUploadQueries->upload(
                $temporaryUploadRecord,
                $request->file('file')
            );

            DB::commit();

            return response()->json([
                'uuid' => $temporaryUploadRecord->uuid,
                'media_id' => $media->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'File upload failed.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function view(Request $request, Media $media)
    {
        $disk = $media->disk; // e.g., 'public' or 'private'
        $path = $media->getPathRelativeToRoot();

        // dd($disk, $path); // Remove debug

        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File not found.');
        }

        $stream = Storage::disk($disk)->readStream($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type'        => $media->mime_type,
            'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
        ]);
    }
}

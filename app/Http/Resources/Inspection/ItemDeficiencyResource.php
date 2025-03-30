<?php

namespace App\Http\Resources\Inspection;

use App\Enums\DeficiencyStatusEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemDeficiencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pertains_to' => $this->pertainsTo,
            'note' => $this->note,
            'action_date' => $this->action_date ? Carbon::parse($this->action_date)->format('d M Y H:i A') : '-',
            'status' => DeficiencyStatusEnum::fromValue($this->status)->description,
            'reported_on' => $this->created_at->format('d M Y H:i A'),
            'is_pending' => $this->is_pending,
            'is_seen' => $this->is_seen,
            'is_attended' => $this->is_attended,
            'comment_by_pertaining_officer' => $this->comment->comment ?? 'No Response'
        ];
    }
}

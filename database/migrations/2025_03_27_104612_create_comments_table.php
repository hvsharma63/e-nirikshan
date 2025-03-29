<?php

use App\Models\Deficiency;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Deficiency::class)->constrained();
            $table->foreignIdFor(User::class, 'comment_by')->constrained();
            $table->tinyText('comment');
            $table->timestamps();
        });
    }
};

<?php

use App\Models\Inspection;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deficiencies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Inspection::class)->constrained();
            $table->foreignIdFor(User::class, 'pertains_to')->constrained();
            $table->boolean('is_viewed')->default(false);
            $table->boolean('is_attended')->default(false);
            $table->date('action_date')->nullable();
            $table->timestamps();
        });
    }
};

<?php

use App\Models\InspectionType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('location');
            $table->date('date');
            $table->foreignIdFor(InspectionType::class)->constrained();
            $table->text('address');
            $table->foreignIdFor(User::class, 'attended_by')->constrained();
            $table->enum('daytime', ['day', 'night']);
            $table->timestamps();
        });
    }
};

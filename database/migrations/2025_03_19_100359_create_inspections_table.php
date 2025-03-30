<?php

use App\Enums\InspectionDayPeriodEnum;
use App\Enums\InspectionStatusEnum;
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
            $table->timestamp('datetime');
            $table->text('address');
            $table->text('note');
            $table->foreignIdFor(User::class, 'attended_by')->constrained();
            $table->tinyInteger('day_period');
            $table->tinyInteger('status');
            $table->boolean('no_deficiencies_found')->default(false);
            $table->timestamps();
        });
    }
};

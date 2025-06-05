<?php

declare(strict_types=1);

use App\Models\Designation;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('user_designations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignId('division_departments_id')->constrained('division_departments');
            $table->foreignIdFor(Station::class)->constrained();
            $table->foreignIdFor(Designation::class)->constrained();
            $table->string('address_asc');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};

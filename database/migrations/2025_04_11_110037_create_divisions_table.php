<?php

use App\Models\Zone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Zone::class)->constrained();
            $table->string('short_name');
            $table->string('full_name');
            $table->timestamps();
        });
    }

};

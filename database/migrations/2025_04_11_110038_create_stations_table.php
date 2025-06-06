<?php

declare(strict_types=1);

use App\Models\Division;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Division::class)->constrained();
            $table->string('full_name');
            $table->string('short_name');
            $table->string('district');
            $table->string('state');
            $table->timestamps();
        });
    }
};

<?php

declare(strict_types=1);

use App\Models\Department;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation');
            $table->string('dob');
            $table->string('mobile_no')->nullable();
            $table->string('pf_no')->default(null);
            $table->foreignIdFor(Department::class)->constrained();
        });
    }

};

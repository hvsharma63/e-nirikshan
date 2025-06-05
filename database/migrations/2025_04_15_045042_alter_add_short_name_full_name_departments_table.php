<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        // Drop foreign key from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');
            $table->dropColumn('designation');
        });

        // Modify departments table
        Schema::table('departments', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->string('full_name');
            $table->string('short_name', 50);
        });

    }

};

<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $department = Department::createOrFirst([
            'name' => 'Accounts',
        ]); 

        User::createOrFirst([
            'name' => 'Arya Kirnendu Kalyanbhai',
            'email' => 'test@test.com',
            'password' => bcrypt('23061990'),
            'designation' => 'Sr.DFM/ACCT/RJT/WR',
            'department_id' => $department->id,
            'dob' => '23/06/1990',
            'pf_no' => '50829802940',
        ]);
    }
}

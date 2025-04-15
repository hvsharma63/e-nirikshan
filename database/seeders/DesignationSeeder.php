<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Division;
use App\Models\Station;
use App\Models\Zone;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedZones();
        $this->seedDivisions();
        $this->seedDepartments();
        $this->seedPivotTable();
        $this->seedStations();
        $this->seedDesignations();
    }

    private function seedZones(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/zones.csv"), "r");

        $zones = collect();

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $zones->push([
                    "id" => $record['2'],
                    "full_name" => $record['0'],
                    "short_name" => $record['1'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $zones->chunk(1000)->each(function ($chunk) {
            Zone::insert($chunk->toArray());
        });
    }

    private function seedDivisions(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/divisions.csv"), "r");

        $divisions = collect();

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $divisions->push([
                    "id" => $record['2'],
                    "full_name" => $record['0'],
                    "short_name" => $record['1'],
                    "zone_id" => $record['3'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $divisions->chunk(1000)->each(function ($chunk) {
            Division::insert($chunk->toArray());
        });
    }

    private function seedDepartments(): void
    {
        DB::table('departments')->truncate();
        // Import data from CSV
        $csvFile = database_path('data/departments.csv');
        if (file_exists($csvFile)) {
            $departments = array_map('str_getcsv', file($csvFile));
            array_shift($departments); // Remove header row
            
            $departmentsCollection = collect();

            foreach ($departments as $department) {
                $departmentsCollection->push([
                    'id' => $department[0],
                    'full_name' => $department[1],
                    'short_name' => $department[2],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $departmentsCollection->chunk(1000)->each(function ($chunk) {
                Department::insert($chunk->toArray());
            });
        }
    }

    private function seedPivotTable(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/division_departments.csv"), "r");

        $divisionDepartments = collect();

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $divisionDepartments->push([
                    "id" => $record['0'],
                    "div_id" => $record['1'],
                    "dept_id" => $record['2'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $divisionDepartments->chunk(1000)->each(function ($chunk) {
            DB::table('division_departments')->insert($chunk->toArray());
        });
    }

    private function seedStations(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/stations.csv"), "r");

        $stations = collect();

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $stations->push([
                    "id" => $record['0'],
                    "full_name" => $record['1'],
                    "short_name" => $record['2'],
                    "division_id" => $record['3'],
                    "district" => $record['4'],
                    "state" => $record['5'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $stations->chunk(1000)->each(function ($chunk) {
            Station::insert($chunk->toArray());
        });
    }

    private function seedDesignations(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/designations.csv"), "r");

        $designations = collect();

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $designations->push([
                    "short_name" => $record['0'],
                    "full_name" => $record['1'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $designations->chunk(1000)->each(function ($chunk) {
            Station::insert($chunk->toArray());
        });
    }

    
}

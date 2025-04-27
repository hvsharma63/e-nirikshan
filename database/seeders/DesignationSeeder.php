<?php

namespace Database\Seeders;

use App\Enums\ModuleEnum;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Division;
use App\Models\DivisionDepartment;
use App\Models\Station;
use App\Models\User;
use App\Models\UserDesignation;
use App\Models\Zone;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DesignationSeeder extends Seeder
{
    public $createdRoles = [];
    public $createdPermissionsForOfficer = [];
    public $createdPermissionsForAdmin = [];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            Schema::disableForeignKeyConstraints();

            // Truncate all tables in reverse order
            User::truncate();
            UserDesignation::truncate();
            Station::truncate();
            DivisionDepartment::truncate();
            Department::truncate();
            Division::truncate();
            Zone::truncate();
            
            Log::info('Starting data seeding...');
            
            $this->seedZones();
            $this->seedDivisions();
            $this->seedDepartments();
            $this->seedPivotTable();
            $this->seedStations();
            $this->seedDesignations();
            $this->seedRoles();
            $this->seedPermissions();
            $this->assignPermissionsToRoles();
            $this->seedUsers();

            Schema::enableForeignKeyConstraints();
            Log::info('Data seeding completed successfully');
        } catch (\Throwable $th) {
            Log::error("Error seeding data: " . $th->getMessage());
            throw $th;
        }
    }

    private function seedZones(): void 
    {
        Log::info('Seeding zones...');
        $heading = true;
        $input_file = fopen(base_path("database/data/zones.csv"), "r");
        
        $zones = collect();
        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE) {
            if (!$heading) {
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

        // Increase chunk size for better performance
        $zones->chunk(5000)->each(function ($chunk) {
            Zone::insert($chunk->toArray());
        });
        Log::info('Zones seeded successfully');
    }

    private function seedDivisions(): void
    {
        Log::info('Seeding divisions...');
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

        $divisions->chunk(5000)->each(function ($chunk) {
            Division::insert($chunk->toArray());
        });
        Log::info('Divisions seeded successfully');
    }

    private function seedDepartments(): void
    {
        Log::info('Seeding departments...');
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

            $departmentsCollection->chunk(5000)->each(function ($chunk) {
                Department::insert($chunk->toArray());
            });
        }
        Log::info('Departments seeded successfully');
    }

    private function seedPivotTable(): void
    {
        Log::info('Seeding division_departments pivot table...');
        $heading = true;
        $input_file = fopen(base_path("database/data/division_departments.csv"), "r");

        $divisionDepartments = collect();

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $divisionDepartments->push([
                    "id" => $record['0'],
                    "division_id" => $record['1'],
                    "department_id" => $record['2'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $divisionDepartments->chunk(5000)->each(function ($chunk) {
            DB::table('division_departments')->insert($chunk->toArray());
        });
        Log::info('Division_departments pivot table seeded successfully');
    }

    private function seedStations(): void
    {
        Log::info('Seeding stations...');
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

        $stations->chunk(5000)->each(function ($chunk) {
            Station::insert($chunk->toArray());
        });
        Log::info('Stations seeded successfully');
    }

    private function seedDesignations(): void
    {
        Log::info('Seeding designations...');
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
                    "level" => $record['2'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);

        $designations->chunk(5000)->each(function ($chunk) {
            Designation::insert($chunk->toArray());
        });
        Log::info('Designations seeded successfully');
    }

    public function seedRoles() {
        Log::info('Seeding roles...');
        $roles = RoleEnum::getValues();
        foreach ($roles as $role) {
            $createdRole = Role::create(['name' => $role, 'guard_name' => 'web']);
            array_push($this->createdRoles, $createdRole);
        }
        Log::info('Roles seeded successfully');
    }

    public function seedPermissions(): void {
        Log::info('Seeding permissions...');
        $permissions = collect([
            PermissionEnum::viewIndexPermissionFor(ModuleEnum::INSPECTIONS()),
            PermissionEnum::createPermissionFor(ModuleEnum::INSPECTIONS()),
            PermissionEnum::viewPermissionFor(ModuleEnum::INSPECTIONS()),
            PermissionEnum::remindPermissionFor(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::viewPermissionFor(ModuleEnum::INSPECTION_NOTE()),
            PermissionEnum::downloadPermissionFor(ModuleEnum::INSPECTION_NOTE()),
            PermissionEnum::viewPermissionFor(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::attendPermissionFor(ModuleEnum::DEFICIENCIES()),
            "view-dashboard",
            "list-users",
        ]);

        $permissions->each(function ($permission) {
            $createdPermission = Permission::create(['name' => $permission, 'guard_name' => 'web']);
            array_push($this->createdPermissionsForOfficer, $createdPermission);
        });

        // Assign permissions to admin roles
        $permissions = collect([
            PermissionEnum::viewAllIndexPermissionFor(ModuleEnum::INSPECTIONS()),
            PermissionEnum::viewAllIndexPermissionFor(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::viewPermissionFor(ModuleEnum::INSPECTION_NOTE()),
            PermissionEnum::downloadPermissionFor(ModuleEnum::INSPECTION_NOTE()),
            PermissionEnum::remindPermissionFor(ModuleEnum::DEFICIENCIES()),
            "view-dashboard-stats",
        ]);

        $permissions->each(function ($permission) {
            $createdPermission = Permission::create(['name' => $permission, 'guard_name' => 'web']);
            array_push($this->createdPermissionsForAdmin, $createdPermission);
        });
        Log::info('Permissions seeded successfully');
    }

    public function assignPermissionsToRoles() {
        Log::info('Assigning permissions to roles...');
        
        $officerRole = Role::where('name', RoleEnum::OFFICER)->first();
        $officerRole->givePermissionTo($this->createdPermissionsForOfficer);
        
        // Assign admin permissions to admin role
        $adminRole = Role::where('name', RoleEnum::ADMIN)->first();
        $adminRole->givePermissionTo($this->createdPermissionsForAdmin);
        
        Log::info('Permissions assigned to roles successfully');
    }

    public function seedUsers() {
        Log::info('Seeding users...');
        $heading = true;
        $input_file = fopen(base_path("database/data/users.csv"), "r");

        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {
                $user = [
                    "name" => $record['0'],
                    "email" => $record['1'],
                    "password" => $record['2'],
                    "dob"=> $record['4'],
                    "mobile_no" => $record['5'],
                    "pf_no" => $record['6'],
                    "created_at" => now(),
                    "updated_at" => now(),
                ];
                $designationBreakdown = explode("/", $record['3']);
                
                $post = Designation::query()
                    ->where('short_name', $designationBreakdown[0])
                    ->first();

                $divDept = DivisionDepartment::query()
                    ->with(['department:id,short_name', 'division:id,short_name'])
                    ->whereRelation('department', 'short_name', $designationBreakdown[1])
                    ->whereRelation('division', 'short_name', 'RJT')
                    ->first();

                
                $station = Station::query()
                    ->with('division:id,short_name')
                    ->whereRelation('division', 'short_name', 'RJT')
                    ->where('short_name', $designationBreakdown[2])
                    ->first();

                Log::info("User: ", $user); 
                $userDesignation = [
                    "division_departments_id" => $divDept->id,
                    "station_id" => $station->id,
                    "designation_id" => $post->id,
                    "address_asc" => $record['3'],
                    "is_active" => true,
                ];

                $userDesignation = new UserDesignation($userDesignation);
                $createdUser = User::create($user);
                $createdUser->userDesignations()->save($userDesignation);

                $createdUser->assignRole(RoleEnum::OFFICER);

            }
            $heading = false;
        }
        fclose($input_file);
        Log::info('Users seeded successfully');
    }
}

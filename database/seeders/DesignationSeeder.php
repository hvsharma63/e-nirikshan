<?php

declare(strict_types=1);

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
    protected array $createdPermissionsForOfficer = [];
    protected array $createdPermissionsForAdmin   = [];

    public function run(): void
    {
        try {
            Schema::disableForeignKeyConstraints();

            // Truncate order reversed to satisfy FKs
            // User::truncate();
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
        $csv = database_path('data/zones.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows); // header

        $zones = collect();
        foreach ($rows as $r) {
            $zones->push([
                'id'         => $r[2],
                'full_name'  => $r[0],
                'short_name' => $r[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $zones->chunk(5000)->each(fn ($chunk) => Zone::insert($chunk->toArray()));
        Log::info('Zones seeded successfully');
    }

    private function seedDivisions(): void
    {
        Log::info('Seeding divisions...');
        $csv = database_path('data/divisions.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows);

        $divs = collect();
        foreach ($rows as $r) {
            $divs->push([
                'id'         => $r[2],
                'full_name'  => $r[0],
                'short_name' => $r[1],
                'zone_id'    => $r[3],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $divs->chunk(5000)->each(fn ($c) => Division::insert($c->toArray()));
        Log::info('Divisions seeded successfully');
    }

    private function seedDepartments(): void
    {
        Log::info('Seeding departments...');
        $csv = database_path('data/departments.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows);

        $deps = collect();
        foreach ($rows as $r) {
            $deps->push([
                'id'         => $r[0],
                'full_name'  => $r[1],
                'short_name' => $r[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $deps->chunk(5000)->each(fn ($c) => Department::insert($c->toArray()));
        Log::info('Departments seeded successfully');
    }

    private function seedPivotTable(): void
    {
        Log::info('Seeding division_departments pivot table...');
        $csv = database_path('data/division_departments.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows);

        $pivots = collect();
        foreach ($rows as $r) {
            $pivots->push([
                'id'                => $r[0],
                'division_id'       => $r[1],
                'department_id'     => $r[2],
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }

        $pivots->chunk(5000)->each(fn ($c) => DB::table('division_departments')->insert($c->toArray()));
        Log::info('Division_departments pivot table seeded successfully');
    }

    private function seedStations(): void
    {
        Log::info('Seeding stations...');
        $csv = database_path('data/stations.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows);

        $stations = collect();
        foreach ($rows as $r) {
            $stations->push([
                'id'          => $r[0],
                'full_name'   => $r[1],
                'short_name'  => $r[2],
                'division_id' => $r[3],
                'district'    => $r[4],
                'state'       => $r[5],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        $stations->chunk(5000)->each(fn ($c) => Station::insert($c->toArray()));
        Log::info('Stations seeded successfully');
    }

    private function seedDesignations(): void
    {
        Log::info('Seeding designations...');
        $csv = database_path('data/designations.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows);

        $designations = collect();
        foreach ($rows as $r) {
            $designations->push([
                'short_name' => $r[0],
                'full_name'  => $r[1],
                'level'      => $r[2],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $designations->chunk(5000)->each(fn ($c) => Designation::insert($c->toArray()));
        Log::info('Designations seeded successfully');
    }

    protected function seedRoles(): void
    {
        Log::info('Seeding roles...');
        foreach (RoleEnum::getValues() as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }
        Log::info('Roles seeded successfully');
    }

    protected function seedPermissions(): void
    {
        Log::info('Seeding permissions…');

        // Officer (own-data)
        $officerPerms = collect([
            // Inspections (own)
            PermissionEnum::listOwn(ModuleEnum::INSPECTIONS()),
            PermissionEnum::viewOwn(ModuleEnum::INSPECTIONS()),
            PermissionEnum::createOwn(ModuleEnum::INSPECTIONS()),
            PermissionEnum::editOwn(ModuleEnum::INSPECTIONS()),

            // Deficiencies (own)
            PermissionEnum::listOwn(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::viewOwn(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::createOwn(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::editOwn(ModuleEnum::DEFICIENCIES()),

            // Misc. module-scoped
            PermissionEnum::remind(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::attend(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::downloadOwn(ModuleEnum::INSPECTION_NOTE()),
            PermissionEnum::viewOwn(ModuleEnum::INSPECTION_NOTE()),

            // Global
            PermissionEnum::viewDashboard(),
            PermissionEnum::listUsers(),
        ]);

        $officerPerms->unique()->each(function (string $perm) {
            $p = Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            $this->createdPermissionsForOfficer[] = $p;
        });

        // Admin (all-data)
        $adminPerms = collect([
            // Inspections (all)
            PermissionEnum::listAll(ModuleEnum::INSPECTIONS()),
            PermissionEnum::viewAll(ModuleEnum::INSPECTIONS()),

            // Deficiencies (all)
            PermissionEnum::listAll(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::viewAll(ModuleEnum::DEFICIENCIES()),

            // Users (all)
            PermissionEnum::listAll(ModuleEnum::USERS()),
            PermissionEnum::viewAll(ModuleEnum::USERS()),

            // Inspection Notes (all)
            PermissionEnum::viewAll(ModuleEnum::INSPECTION_NOTE()),
            PermissionEnum::downloadAll(ModuleEnum::INSPECTION_NOTE()),

            // Misc. module-scoped
            PermissionEnum::remind(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::attend(ModuleEnum::DEFICIENCIES()),
            PermissionEnum::download(ModuleEnum::INSPECTION_NOTE()),

            // Global
            PermissionEnum::viewDashboard(),
            PermissionEnum::viewDashboardStats(),
        ]);

        $adminPerms->unique()->each(function (string $perm) {
            $p = Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
            $this->createdPermissionsForAdmin[] = $p;
        });

        Log::info('Permissions seeded successfully');
    }


    protected function assignPermissionsToRoles(): void
    {
        Log::info('Assigning permissions to roles…');

        $officer = Role::where('name', RoleEnum::OFFICER)->first();
        $admin   = Role::where('name', RoleEnum::ADMIN)->first();

        if ($officer) {
            $officer->syncPermissions($this->createdPermissionsForOfficer);
        }

        if ($admin) {
            $admin->syncPermissions($this->createdPermissionsForAdmin);
        }

        Log::info('Permissions assigned successfully');
    }

    private function seedUsers(): void
    {
        Log::info('Seeding users...');
        $csv = database_path('data/users.csv');
        if (! file_exists($csv)) {
            return;
        }

        $rows = array_map('str_getcsv', file($csv));
        array_shift($rows);

        $users = User::select(['id','pf_no'])
            ->with(['userDesignations', 'roles'])
            ->get()
            ->keyBy('pf_no');

        foreach ($rows as $r) {
            $user = $users[$r[6]];
            $designationParts = explode('/', $r[3]);
            $post = Designation::where('short_name', $designationParts[0])->first();
            $divDept = DivisionDepartment::whereHas('division', fn ($q) => $q->where('short_name', 'RJT'))
                         ->whereHas('department', fn ($q) => $q->where('short_name', $designationParts[1]))
                         ->first();
            $station = Station::whereHas('division', fn ($q) => $q->where('short_name', 'RJT'))
                         ->where('short_name', $designationParts[2])
                         ->first();

            $user->userDesignations()->create([
                'division_departments_id' => $divDept->id,
                'station_id'              => $station->id,
                'designation_id'          => $post->id,
                'address_asc'             => $r[3],
                'is_active'               => true,
            ]);

            $user->assignRole(RoleEnum::OFFICER);
        }

        Log::info('Users seeded successfully');
    }
}

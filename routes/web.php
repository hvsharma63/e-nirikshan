<?php

use App\Enums\ModuleEnum;
use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Admin\DeficiencyController as AdminDeficiencyController;
use App\Http\Controllers\Admin\InspectionController as AdminInspectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Officer\DeficiencyController as OfficerDeficiencyController;
use App\Http\Controllers\Officer\InspectionController as OfficerInspectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    //
    // Dashboard
    //
    Route::prefix('dashboard')
        ->name('dashboard.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])
                ->middleware(['permissions:' . PermissionEnum::viewDashboard()])
                ->name('index');

            Route::get('/stats', [DashboardController::class, 'stats'])
                ->middleware(['permissions:' . PermissionEnum::viewDashboardStats()])
                ->name('stats');
        });

    //
    // Users (branch officers listing)
    //
    Route::prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/branch-officers', [RegisteredUserController::class,'listBranchOfficers'])
                ->middleware(['permissions:' . PermissionEnum::listUsers()])
                ->name('list-branch-officers');
        });

    //
    // Officer Routes (own-data only)
    //
    Route::middleware(['roles:' . RoleEnum::OFFICER])
        ->name('officer.')
        ->group(function() {
        // Inspections
        Route::prefix('inspections')
            ->name('inspections.')
            ->group(function () {
                Route::get('/', [OfficerInspectionController::class, 'index'])
                    ->middleware(['permissions:' . PermissionEnum::listOwn(ModuleEnum::INSPECTIONS())])
                    ->name('index');

                Route::get('/list', [OfficerInspectionController::class, 'list'])
                    ->middleware(['permissions:' . PermissionEnum::listOwn(ModuleEnum::INSPECTIONS())])
                    ->name('list');

                Route::get('/create', [OfficerInspectionController::class, 'create'])
                    ->middleware(['permissions:' . PermissionEnum::createOwn(ModuleEnum::INSPECTIONS())])
                    ->name('create');

                Route::post('/', [OfficerInspectionController::class, 'save'])
                    ->middleware(['permissions:' . PermissionEnum::createOwn(ModuleEnum::INSPECTIONS())])
                    ->name('save');

                Route::get('/{id}', [OfficerInspectionController::class, 'view'])
                    ->middleware(['permissions:' . PermissionEnum::viewOwn(ModuleEnum::INSPECTIONS())])
                    ->name('view');

                Route::get('/{id}/view-note', [OfficerInspectionController::class, 'viewNote'])
                    ->middleware(['permissions:' . PermissionEnum::viewOwn(ModuleEnum::INSPECTION_NOTE())])
                    ->name('view-note');

                Route::get('/{id}/download-note', [OfficerInspectionController::class, 'downloadNote'])
                    ->middleware(['permissions:' . PermissionEnum::downloadOwn(ModuleEnum::INSPECTION_NOTE())])
                    ->name('download-note');
            });

        // Deficiencies
        Route::prefix('deficiencies')
            ->name('deficiencies.')
            ->group(function () {
                Route::get('/', [OfficerDeficiencyController::class, 'index'])
                    ->middleware(['permissions:' . PermissionEnum::listOwn(ModuleEnum::DEFICIENCIES())])
                    ->name('index');

                Route::get('/list', [OfficerDeficiencyController::class, 'list'])
                    ->middleware(['permissions:' . PermissionEnum::listOwn(ModuleEnum::DEFICIENCIES())])
                    ->name('list');

                Route::get('/{id}', [OfficerDeficiencyController::class, 'view'])
                    ->middleware(['permissions:' . PermissionEnum::viewOwn(ModuleEnum::DEFICIENCIES())])
                    ->name('view');

                Route::get('/{id}/view-note', [OfficerDeficiencyController::class, 'viewNote'])
                    ->middleware(['permissions:' . PermissionEnum::viewOwn(ModuleEnum::INSPECTION_NOTE())])
                    ->name('view-note');

                Route::get('/{id}/download-note', [OfficerDeficiencyController::class, 'downloadNote'])
                    ->middleware(['permissions:' . PermissionEnum::downloadOwn(ModuleEnum::INSPECTION_NOTE())])
                    ->name('download-note');

                Route::post('/{id}/remind', [OfficerDeficiencyController::class, 'remind'])
                    ->middleware(['permissions:' . PermissionEnum::remind(ModuleEnum::DEFICIENCIES())])
                    ->name('remind');

                Route::post('/{id}/attend', [OfficerDeficiencyController::class, 'attend'])
                    ->middleware(['permissions:' . PermissionEnum::attend(ModuleEnum::DEFICIENCIES())])
                    ->name('attend');
            });
    });

    //
    // Admin Routes (all-data)
    //
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['roles:' . RoleEnum::ADMIN])
        ->group(function() {

        // Inspections
        Route::prefix('inspections')
            ->name('inspections.')
            ->group(function () {
                Route::get('', [AdminInspectionController::class, 'index'])
                    ->middleware(['permissions:' . PermissionEnum::listAll(ModuleEnum::INSPECTIONS())])
                    ->name('index');

                Route::get('/list', [AdminInspectionController::class, 'list'])
                    ->middleware(['permissions:' . PermissionEnum::listAll(ModuleEnum::INSPECTIONS())])
                    ->name('list');

                Route::get('/{id}', [AdminInspectionController::class, 'view'])
                    ->middleware(['permissions:' . PermissionEnum::viewAll(ModuleEnum::INSPECTIONS())])
                    ->name('view');

            });

        // Deficiencies
        Route::prefix('deficiencies')
            ->name('deficiencies.')
            ->group(function () {
                Route::get('/', [AdminDeficiencyController::class, 'index'])
                    ->middleware(['permissions:' . PermissionEnum::listAll(ModuleEnum::DEFICIENCIES())])
                    ->name('index');

                Route::get('/list', [AdminDeficiencyController::class, 'list'])
                    ->middleware(['permissions:' . PermissionEnum::listAll(ModuleEnum::DEFICIENCIES())])
                    ->name('list');

                Route::get('/{id}', [AdminDeficiencyController::class, 'view'])
                    ->middleware(['permissions:' . PermissionEnum::viewAll(ModuleEnum::DEFICIENCIES())])
                    ->name('view');
            });

        // Users (admin)
        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('', [UserController::class, 'index'])
                    ->middleware(['permissions:' . PermissionEnum::listAll(ModuleEnum::USERS())])
                    ->name('index');

                Route::get('/list', [UserController::class, 'list'])
                    ->middleware(['permissions:' . PermissionEnum::listAll(ModuleEnum::USERS())])
                    ->name('list');

                Route::get('/{userId}/details', [UserController::class, 'details'])
                    ->middleware(['permissions:' . PermissionEnum::viewAll(ModuleEnum::USERS())])
                    ->name('details');

            });

            Route::prefix('notes')
                ->name('notes.')
                ->group(function () {
                    Route::get('/inspection/{userId}/{inspectionId}/view', [AdminInspectionController::class, 'viewNote'])
                        ->middleware(['permissions:' . PermissionEnum::viewAll(ModuleEnum::INSPECTION_NOTE())])
                        ->name('view');

                    Route::get('/inspection/{userId}/{inspectionId}/download', [AdminInspectionController::class, 'downloadNote'])
                        ->middleware(['permissions:' . PermissionEnum::downloadAll(ModuleEnum::INSPECTION_NOTE())])
                        ->name('download');
                });
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

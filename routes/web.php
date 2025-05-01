<?php

use App\Http\Controllers\Admin\DeficiencyController as AdminDeficiencyController;
use App\Http\Controllers\Admin\InspectionController as AdminInspectionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Officer\DeficiencyController as OfficerDeficiencyController;
use App\Http\Controllers\Officer\InspectionController as OfficerInspectionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard.index');
    }
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::prefix('dashboard')
        ->name('dashboard.')
        ->group(function () {
            
            Route::get('/', [DashboardController::class, 'index'])
                ->middleware(['permissions:view-dashboard'])
                ->name('index');
                
            Route::get('/stats', [DashboardController::class, 'stats'])
                ->middleware(['permissions:view-dashboard-stats'])
                ->name('stats');
    });

    // Users routes
    Route::prefix('users')
        ->name('users.')
        ->group(function () {
            Route::get('/branch-officers', [RegisteredUserController::class,'listBranchOfficers'])
                ->middleware(['permissions:list-users'])
                ->name('list-branch-officers');
    });
    
    // Officer routes
    Route::name('officers.')
        ->middleware(['roles:officer'])->group(function() {
     
            // Inspections routes
            Route::prefix('inspections')
                ->name('inspections.')
                ->group(function () {
                    Route::get('/', [OfficerInspectionController::class, 'index'])
                        ->middleware(['permissions:view-index-inspections'])
                        ->name('index');
                    
                    Route::post('/', [OfficerInspectionController::class, 'save'])
                        ->middleware(['permissions:create-inspections'])
                        ->name('save');
                    
                    Route::get('/list', [OfficerInspectionController::class, 'list'])
                        ->middleware(['permissions:view-index-inspections'])
                        ->name('list');
                    
                    Route::get('/create', [OfficerInspectionController::class, 'create'])
                        ->middleware(['permissions:create-inspections'])
                        ->name('create');
        
                    Route::get('/{id}', [OfficerInspectionController::class, 'view'])
                        ->middleware(['permissions:view-inspections'])
                        ->name('view');
                    
                    Route::get('/{id}/view-note', [OfficerInspectionController::class, 'viewNote'])
                        ->middleware(['permissions:view-inspection-note'])
                        ->name('view-note');
                
                    Route::get('/{id}/download-note', [OfficerInspectionController::class, 'downloadNote'])
                        ->middleware(['permissions:download-inspection-note'])
                        ->name('download-note');
            });

            Route::prefix('deficiencies')
                ->name('deficiencies.')
                ->group(function () {
                    Route::get('/', [OfficerDeficiencyController::class, 'index'])
                        ->name('index');
                    
                    Route::get('/list', [OfficerDeficiencyController::class, 'list'])
                        ->name('list');
                    
                    Route::get('/{id}', [OfficerDeficiencyController::class, 'view'])
                        ->name('view');
                    
                    Route::get('/{id}/view-note', [OfficerDeficiencyController::class, 'viewNote'])
                        ->name('view-note');
                    
                    Route::get('/{id}/download-note', [OfficerDeficiencyController::class, 'downloadNote'])
                        ->name('download-note');
                    
                    Route::post('/{id}/remind', [OfficerDeficiencyController::class, 'remind'])
                        ->name('remind');
                    
                    Route::post('/{id}/attend', [OfficerDeficiencyController::class, 'attend'])
                        ->name('attend');
            });
    });

    // Admin routes
    Route::prefix('admin')
        ->name('admin.')
        ->middleware(['roles:admin'])->group(function() {
        
        // Inspections routes
        Route::prefix('inspections')
            ->name('inspections.')
            ->group(function () {
                Route::get('', [AdminInspectionController::class, 'index'])
                    ->middleware(['permissions:view-all-index-inspections'])
                    ->name('index');

                Route::get('/list', [AdminInspectionController::class, 'list'])
                    ->middleware(['permissions:view-all-index-inspections'])
                    ->name('list');
                
                Route::get('/{id}', [AdminInspectionController::class, 'view'])
                    ->middleware(['permissions:view-inspections'])
                    ->name('view');
        });

        Route::prefix('deficiencies')
            ->name('deficiencies.')
            ->group(function () {
                Route::get('/', [AdminDeficiencyController::class, 'index'])
                    ->name('index');

                Route::get('/list', [AdminDeficiencyController::class, 'list'])
                    ->name('list');

                Route::get('/{id}', [AdminDeficiencyController::class, 'view'])
                    ->name('view');
        });

        Route::prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('', [UserController::class, 'index'])
                    ->name('index');

                Route::get('list', [UserController::class, 'list'])
                    ->name('list');
                
                Route::get('/{userId}/details', [UserController::class, 'details'])
                    ->name('details');

                Route::get('/{userId}/inspections/{inspectionId}/view-note', [AdminInspectionController::class, 'viewNote'])
                    ->middleware(['permissions:view-inspection-note'])
                    ->name('inspections.view-note');
        
                Route::get('/{userId}/inspections/{inspectionId}/download-note', [AdminInspectionController::class, 'downloadNote'])
                    ->middleware(['permissions:download-inspection-note'])
                    ->name('inspections.download-note');
        });
    });

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

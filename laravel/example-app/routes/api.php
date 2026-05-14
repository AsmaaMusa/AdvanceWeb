<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminProjectController;
use App\Http\Controllers\AdminReportsOverviewController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function (): void {
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function (): void {
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
        Route::get('/users', [AdminUserController::class, 'index']);
        Route::post('/users', [AdminUserController::class, 'store']);
        Route::patch('/users/{user}', [AdminUserController::class, 'update']);
        Route::post('/projects', [AdminProjectController::class, 'store']);
        Route::get('/reports-overview', [AdminReportsOverviewController::class, 'index']);
        Route::get('/reports-overview/export', [AdminReportsOverviewController::class, 'export']);
        Route::get('/reports-overview/analytics', [AdminReportsOverviewController::class, 'analytics']);
        Route::get('/reports-overview/filter-options', [AdminReportsOverviewController::class, 'filterOptions']);
        Route::get('/settings', [AdminSettingsController::class, 'show']);
        Route::put('/settings', [AdminSettingsController::class, 'update']);
    });
});

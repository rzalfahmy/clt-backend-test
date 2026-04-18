<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\LayerController;
use App\Http\Controllers\LayupController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SupplierImportConflictController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : view('welcome');
});

Route::middleware(['auth', 'can:manage-clt-data'])->scopeBindings()->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/layups', [CatalogController::class, 'layups'])->name('layups.index');
    Route::get('/layers', [CatalogController::class, 'layers'])->name('layers.index');
    Route::resource('suppliers', SupplierController::class);
    Route::get('suppliers/{supplier}/export', [SupplierController::class, 'export'])->name('suppliers.export');
    Route::post('suppliers/{supplier}/import', [SupplierController::class, 'import'])->name('suppliers.import');
    Route::get(
        'suppliers/{supplier}/imports/{token}/conflicts/{index?}',
        [SupplierImportConflictController::class, 'show']
    )->name('suppliers.import-conflicts.show');
    Route::post(
        'suppliers/{supplier}/imports/{token}/conflicts/{index}',
        [SupplierImportConflictController::class, 'resolve']
    )->name('suppliers.import-conflicts.resolve');
    Route::resource('suppliers.layups', LayupController::class)->except(['index']);
    Route::resource('suppliers.layups.layers', LayerController::class)->except(['index', 'show']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

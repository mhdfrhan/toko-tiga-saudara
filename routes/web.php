<?php

use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\SupplierController;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/dashboard');
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/profile', 'profile')->name('profile');

    Route::middleware(['role:owner'])->group(function () {
        Route::get('/monitoring', [DashboardController::class, 'monitoring'])->name('monitoring');

        Route::get('users', [UserController::class, 'index'])->name('users');

        Route::get('/approvals', [DashboardController::class, 'approvals'])->name('approvals');
        Route::put('/approvals/{id}/approve', [DashboardController::class, 'approve'])->name('approvals.approve');
        Route::put('/approvals/{id}/reject', [DashboardController::class, 'reject'])->name('approvals.reject');
    });

    Route::middleware(['role:admin,owner'])->group(function () {
        Route::prefix('products')->group(function () {
            Route::get('/categories', [ProductController::class, 'categories'])->name('products.categories');
            Route::get('/', [ProductController::class, 'index'])->name('products');
        });

        Route::prefix('suppliers')->group(function () {
            Route::get('/', [SupplierController::class, 'index'])->name('suppliers');
        });

        Route::prefix('stock-in')->group(function () {
            Route::get('/', [BarangMasukController::class, 'index'])->name('stock-in');
            Route::get('/create', [BarangMasukController::class, 'create'])->name('stock-in.create');
            Route::get('/show/{id}', [BarangMasukController::class, 'show'])->name('barang-masuk.show');
        });

        Route::prefix('reports')->group(function () {
            Route::get('/suppliers', [ReportController::class, 'suppliers'])->name('reports.suppliers');
            Route::get('/suppliers/{reportId}', [ReportController::class, 'suppliersShow'])->name('laporan.supplier.show');
            // print
            Route::get('/suppliers/{report}/print', [ReportController::class, 'suppliersPrint'])->name('laporan.supplier.print');


            Route::get('/sales', [ReportController::class, 'sales'])->name('reports.sales');
            Route::get('/sales/{reportId}', [ReportController::class, 'salesShow'])->name('laporan.sales.show');
            Route::get('/sales/{report}/print', [ReportController::class, 'salesPrint'])->name('laporan.sales.print');




            Route::get('/stocks', [ReportController::class, 'stocks'])->name('reports.stocks');
            Route::get('/profits', [ReportController::class, 'profits'])->name('reports.profits');
        });
    });

    Route::middleware(['role:kasir'])->group(function () {
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir');
        Route::post('/kasir/add-item', [KasirController::class, 'addItem'])->name('kasir.add-item');
    });

    Route::get('/print/sale/{penjualan}', function (Penjualan $penjualan) {
        return view('print.penjualan', compact('penjualan'));
    })->name('print.sale');
});

require __DIR__ . '/auth.php';

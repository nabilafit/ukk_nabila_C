<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use App\Models\Loan;

Route::get('/', function () {
    return view('welcome');
});

// route login
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// route halaman dashboard
Route::get('/admin', function() {
    $totalBarang = Item::count();
    $dipinjam = Loan::where('status', 'dipinjam')->count();
    $kembali = Loan::where('status', 'kembali')->count();

    return view('admin', compact('totalBarang', 'dipinjam', 'kembali'));
})->middleware('role:admin');

Route::get('/petugas', function() {
    return view('petugas');
})->middleware('role:petugas');

Route::get('/siswa', function() {
    return view('siswa');
})->middleware('role:siswa');

// route loans, borrow, return
Route::get('/loans', [LoanController::class, 'index'])
     ->middleware('role:admin,petugas,siswa');

// transaksi admin dan petugas 
Route::post('/borrow', [LoanController::class, 'store'])
     ->middleware('role:admin,petugas,siswa');

// status 
Route::get('/return/{id}', [LoanController::class, 'return'])
     ->middleware('role:admin,petugas');

Route::get('/rusak/{id}', [LoanController::class, 'rusak'])
     ->middleware('role:admin,petugas');

Route::get('/hilang/{id}', [LoanController::class, 'hilang'])
     ->middleware('role:admin,petugas');

// route admin tambah, edit dan hapus
Route::get('/items', [ItemController::class, 'index'])
     ->middleware('role:admin');

Route::post('/items/add', [ItemController::class, 'store'])
     ->middleware('role:admin');

Route::get('/items/delete/{id}', [ItemController::class, 'delete'])
     ->middleware('role:admin');

// route laporan admin
Route::get('/report', function () {
    $loans = App\Models\Loan::with(['item', 'user'])->get();
    return view('report', compact('loans'));
})->middleware('role:admin');
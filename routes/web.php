<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// route login
Route::get('/login', [AuthController::class, 'showlogin']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// route halaman dashboard
Route::get('/admin', function() {
    $totalBarang = Item::count();
    $dipinjam = Loan::where('status', 'dipinjam')->count();
    $kembali = Loan::where('status', 'kembali')->count();

    return view('admin', compact('total_Barang', 'dipinjam', 'kembali'));
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
     -middleware('role:admin,petugas');

Route::get('/rusak/{id}', [LoanController::class, 'rusak'])
     -middleware('role:admin,petugas');

Route::get('/hilang/{id}', [LoanController::class, 'hilang'])
     -middleware('role:admin,petugas');
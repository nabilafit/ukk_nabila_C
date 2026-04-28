<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ItemController;
use App\Models\Item;
use App\Models\Loan;
use Carbon\Carbon;

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

// route petugas
Route::get('/petugas', function () {

    return view('petugas'); 

})->middleware('role:petugas');

// route siswa
Route::get('/siswa', function () {

    $loans = Loan::with('item')
        ->where('user_id', session('user_id'))
        ->get();

    foreach ($loans as $loan) {
        $loan->is_late = (
            $loan->status == 'dipinjam'
            && $loan->due_date
            && Carbon::parse($loan->due_date)->isPast()
        );
    }

    return view('siswa', compact('loans'));
});

// route loans, borrow, return
Route::get('/loans', [LoanController::class, 'index'])
    ->middleware('role:admin,petugas,siswa');

// transaksi admin dan petugas 
Route::post('/borrow', [LoanController::class, 'store'])
    ->middleware('role:admin,petugas,siswa');

// status 
Route::get('/return/{id}', [LoanController::class, 'returnBook'])
    ->middleware('role:admin,petugas');

// route rusak
Route::get('/rusak/{id}', [LoanController::class, 'rusak'])
    ->middleware('role:admin,petugas');

//route hilang
Route::get('/hilang/{id}', [LoanController::class, 'hilang'])
    ->middleware('role:admin,petugas');

// route admin tambah, edit dan hapus
Route::get('/items', [ItemController::class, 'index'])
     ->middleware('role:admin');

Route::post('/items/add', [ItemController::class, 'store'])
     ->middleware('role:admin');

Route::get('/items/edit/{id}', [ItemController::class, 'edit'])
     ->middleware('role:admin');

Route::post('/items/update/{id}', [ItemController::class, 'update'])
     ->middleware('role:admin');

Route::get('/items/delete/{id}', [ItemController::class, 'delete'])
     ->middleware('role:admin');

// route laporan admin
Route::get('/report', function () {
    $loans = App\Models\Loan::with(['item', 'user'])->get();
    return view('report', compact('loans'));
})->middleware('role:admin');

// register 
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

// route search autocomplete (mirip google)
Route::get('/search-items', [LoanController::class, 'searchItems']);

// route pembayaran 
Route::get('/bayar/{id}', [LoanController::class, 'bayar'])
    ->middleware('role:admin,petugas');

// route edit buku
Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->middleware('role:admin');
Route::post('/items/update/{id}', [ItemController::class, 'update'])->middleware('role:admin');
<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LoanController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
{
    $items = Item::where('stock', '>', 0)
        ->when($request->search, function ($q) use ($request) {
            $q->where('name', 'like', '%' . trim($request->search) . '%');
        })
        ->get();

    // FIX: query harus dibuat dulu
    $query = Loan::with('item');

    // siswa hanya lihat punyanya sendiri
    if (session('role') === 'siswa') {
        $query->where('user_id', session('user_id'));
    }

    $loans = $query->get();

    foreach ($loans as $loan) {
        $loan->is_late =
            $loan->status === 'dipinjam' &&
            $loan->due_date &&
            Carbon::parse($loan->due_date)->isPast();
    }

    return view('loans', compact('items', 'loans'));
}

    // =========================
    // PINJAM
    // =========================
    public function store(Request $request)
{
    $item = Item::findOrFail($request->item_id);

    // 🔥 cari user dari NIS
    $user = User::where('nis', $request->nis)->first();

    if (!$user) {
        return back()->with('error', 'NIS tidak ditemukan!');
    }

    if ($item->stock < $request->jumlah) {
        return back()->with('error', 'Stok tidak cukup!');
    }

    $item->decrement('stock', $request->jumlah);

    Loan::create([
        'user_id' => $user->id,
        'item_id' => $request->item_id,

        'nama_peminjam' => $user->name,

        'jumlah' => $request->jumlah,
        'borrow_date' => now(),
        'due_date' => now()->addDays(3),
        'status' => 'dipinjam',
        'denda' => 0,
        'is_paid' => false,
    ]);

    return back()->with('success', 'Berhasil meminjam!');
}

    // =========================
    // RETURN
    // =========================
    public function returnBook($id)
{
    $loan = Loan::findOrFail($id);

    $loan->update([
        'status' => 'kembali',
        'return_date' => now()
    ]);

    $loan->item->increment('stock', $loan->jumlah);

    return back()->with('success', 'Buku dikembalikan');
}

    // =========================
    // RUSAK
    // =========================
    public function rusak($id)
{
    $loan = Loan::findOrFail($id);

    $loan->update([
        'status' => 'rusak',
        'denda' => 50000 
    ]);

    return back()->with('success', 'Buku rusak dicatat');
}

    // =========================
    // HILANG
    // =========================
    public function hilang($id)
{
    $loan = Loan::findOrFail($id);

    $loan->update([
        'status' => 'hilang',
        'denda' => 50000 
    ]);

    return back()->with('success', 'Buku ditandai hilang');
}

    // =========================
    // BAYAR DENDA
    // =========================
    public function bayar($id)
{
    $loan = Loan::findOrFail($id);

    $loan->update([
        'is_paid' => true,
        'paid_at' => now(),
        'denda' => 0
    ]);

    return back()->with('success', 'Pembayaran berhasil (LUNAS)');
}

    // =========================
    // SEARCH AUTOCOMPLETE
    // =========================
    public function searchItems(Request $request)
    {
        $items = Item::where('name', 'like', '%' . $request->search . '%')
            ->get(['id', 'name']);

        return response()->json($items);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Item;
use App\Models\Loan;
use Illuminate\Http\Request;



class LoanController extends Controller
{
    public function index(Request $request)
    {
        // searching barang 
        $items = Item::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->get();

        // role filter 
        if (session('role') == 'siswa') {
            $loans = Loan::with('item')
               ->where('user_id', session('user_id'))
               ->get();
        } else {
            $loans = Loan::with('item')->get();
        }

        return view('loans', compact('items', 'loans'));
    }

    // transaksi pake (nis)
    public function store(Request $request)
    {
        $nis = trim($request->nis);

        $user = User::where('nis', $nis)
            ->where('role', 'siswa')
            ->first();

        if (!$user) {
            return back()->with('error', 'NIS tidak ditemukan!');
        }

        $item = Item::find($request->item_id);

        if (!$item || $item->stock <= 0) {
            return back()->with('error', 'Stok habis!');
        }

        Loan::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'nama_peminjam' => $user->name,
            'borrow_date' => now(),
            'due_date' => now()->addDays(3),
            'status' => 'dipinjam'
        ]);

        $item->decrement('stock');

        return back()->with('success', 'Transaksi berhasil!');
    }

    // kembali 
    public function return($id)
    {
        $loan = Loan::find($id);

        $loan->update([
            'status' => 'kembali',
            'return_date' => now()
        ]);

        $loan->item->increment('stock');

        return back()->with('success', 'Barang dikembalikan!');
    }

    // barang rusak 
    public function rusak($id)
    {
        $loan = Loan::find($id);

        $denda = 50000;

        $loan->update([
            'status' => 'rusak',
            'denda' => $denda
        ]);

        return back()->with('success', 'Barang rusak! Denda Rp ' . $denda);
    }

    // barang hilang 
    public function hilang($id)
    {
        $loan = Loan::find($id);

        $denda = 100000;

        $loan->update([
            'status' => 'hilang',
            'denda' => $denda
        ]);

        return back()->with('success', 'Barang hilang! Denda Rp ' . $denda);
    }
}

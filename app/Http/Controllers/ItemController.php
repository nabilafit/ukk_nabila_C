<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();
        return view('items', compact('items'));
    }

    public function store(Request $request)
    {
        Item::create([
            'name' => $request->name,
            'stock' => $request->stock
        ]);

        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function delete($id)
    {
        Item::find($id)->delete();
        return back()->with('success', 'Barang dihapus!');
    }
}

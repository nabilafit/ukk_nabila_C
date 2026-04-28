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
        $request->validate([
            'name' => 'required',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        Item::create([
            'name' => $request->name,
            'stock' => $request->stock,
            'image' => $path
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan');
    }

    public function delete($id)
    {
        $item = Item::findOrFail($id);

        if (method_exists($item, 'loans') && $item->loans()->exists()) {
            return back()->with('error', 'Buku masih dipakai peminjaman!');
        }

        $item->delete();

        return back()->with('success', 'Buku berhasil dihapus');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        return view('items_edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $item = Item::findOrFail($id);

        $path = $item->image;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
        }

        $item->update([
            'name' => $request->name,
            'stock' => $request->stock,
            'image' => $path
        ]);

        return redirect('/items')->with('success', 'Buku berhasil diupdate');
    }
}
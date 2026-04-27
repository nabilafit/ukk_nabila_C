<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Kelola Barang</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORM TAMBAH --}}
    <form method="POST" action="/items/add" class="mb-4">
        @csrf
        <input type="text" name="name" placeholder="Nama Barang" required>
        <input type="number" name="stock" placeholder="Stok" required>
        <button class="btn btn-primary">Tambah</button>
    </form>

    {{-- TABEL --}}
    <table class="table table-bordered">
        <tr>
            <th>Nama</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        @foreach($items as $item)
        <tr>
            <td>{{ $item->name }}</td>
            <td>{{ $item->stock }}</td>
            <td>

                <a href="/items/delete/{{ $item->id }}" class="btn btn-danger btn-sm">Hapus</a>
            </td>
        </tr>
        @endforeach
    </table>

</div>

</body>
</html>
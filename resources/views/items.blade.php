<!DOCTYPE html>
<html>
<head>
    <title>Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Kelola Buku</span>
    </div>
</nav>

<div class="container mt-4">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- FORM TAMBAH --}}
    <form method="POST" action="/items/add" enctype="multipart/form-data" class="mb-4">
        @csrf

        <input type="file" name="image" class="form-control mb-2">
        <input type="text" name="name" class="form-control mb-2" placeholder="Nama Buku">
        <input type="number" name="stock" class="form-control mb-2" placeholder="Stok">

        <button class="btn btn-primary">Tambah</button>
    </form>

    {{-- TABLE --}}
    <table class="table table-bordered text-center align-middle">
        <thead class="table-dark">
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
        @if(isset($items))
            @foreach($items as $item)
                <tr>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" width="70">
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $item->name }}</td>

                    <td class="text-end">{{ $item->stock }}</td>

                    <td>
                        <a href="/items/edit/{{ $item->id }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="/items/delete/{{ $item->id }}" class="btn btn-danger btn-sm">Hapus</a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

</div>

</body>
</html>
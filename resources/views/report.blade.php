<!DOCTYPE html>
<html>
<head>
    <title>Laporan Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Laporan Peminjaman</span>
        <a href="/admin" class="btn btn-light btn-sm">Kembali</a>
    </div>
</nav>

<div class="container mt-4">

    <h3 class="mb-3">Data Peminjaman</h3>

    <div class="card shadow">
        <div class="card-body">

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Peminjam</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                        <th>Tanggal Kembali</th>
                    </tr>
                </thead>

                <tbody>
    @foreach($loans as $index => $loan)
    <tr>
        <td>{{ $index + 1 }}</td>

        <td>{{ $loan->item->name ?? '-' }}</td>

        <td>{{ $loan->user->name ?? '-' }}</td>

        <td>{{ $loan->borrow_date ?? '-' }}</td>

        <td>
            @if($loan->status == 'dipinjam')
                <span class="badge bg-warning">Dipinjam</span>
            @elseif($loan->status == 'kembali')
                <span class="badge bg-success">Kembali</span>
            @elseif($loan->status == 'hilang')
                <span class="badge bg-danger">Hilang</span>
            @elseif($loan->status == 'rusak')
                <span class="badge bg-dark">Rusak</span>
            @endif
        </td>

        <td>
            {{ $loan->return_date 
                ? \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') 
                : '-' }}
        </td>
    </tr>
    @endforeach
</tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>
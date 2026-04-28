<!DOCTYPE html>
<html>
<head>
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">PERPUSTAKAAN DIGITAL</span>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h3>Selamat Datang di Dashboard {{ session('name') }}</h3>

    {{-- STATISTIK --}}
    <div class="row mt-4">

        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white text-center shadow">
                <div class="card-body">
                    <h5>Total Buku</h5>
                    <h2>{{ $totalBarang ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-secondary text-white text-center shadow">
                <div class="card-body">
                    <h5>Sedang Dipinjam</h5>
                    <h2>{{ $dipinjam ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white text-center shadow">
                <div class="card-body">
                    <h5>Sudah Kembali</h5>
                    <h2>{{ $kembali ?? 0 }}</h2>
                </div>
            </div>
        </div>

    </div>

    {{-- MENU --}}
    <div class="row mt-4">

        {{-- KELOLA BUKU --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5>Kelola Buku</h5>
                    <p class="text-muted">Tambah, edit, hapus buku</p>
                    <a href="/items" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>

        {{-- DATA PEMINJAMAN --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5>Data Peminjaman</h5>
                    <p class="text-muted">Kelola semua transaksi</p>
                    <a href="/loans" class="btn btn-secondary">Lihat</a>
                </div>
            </div>
        </div>

        {{-- LAPORAN --}}
        <div class="col-md-4 mb-3">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <h5>Laporan</h5>
                    <p class="text-muted">Rekap peminjaman</p>
                    <a href="/report" class="btn btn-info">Lihat</a>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
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

    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <h5>Total Barang</h5>
                    <h2>{{ $totalBarang }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-secondary text-white text-center">
                <div class="card-body">
                    <h5>Sedang Dipinjam</h5>
                    <h2>{{ $dipinjam }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-info text-white text-center">
                <div class="card-body">
                    <h5>Sudah Kembali</h5>
                    <h2>{{ $kembali }}</h2>
                </div>
            </div>
        </div>

    </div>

    <!-- menu bawah -->
    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h5>Kelola Barang</h5>
                    <a href="/items" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h5>Data Peminjaman</h5>
                    <a href="/loans" class="btn btn-secondary">Lihat</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
        <div class="card shadow">
            <div class="card-body text-center">
                <h5>Laporan</h5>
                <a href="/report" class="btn btn-info">Lihat</a>
            </div>
        </div>
    </div>

    </div>

</div>

</body>
</html>
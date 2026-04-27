<!DOCTYPE html>
<html>
<head>
    <title>Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-success">
    <div class="container-fluid">
        <span class="navbar-brand">Dashboard Peminjaman Perpustakaan Digital Siswa</span>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Selamat Datang, {{ session('name') }}</h3>

    <div class="card mt-3">
        <div class="card-body text-center">
            <h5>Pinjam Barang</h5>
            <a href="/loans" class="btn btn-primary">Mulai</a>
        </div>
    </div>
</div>

</body>
</html>
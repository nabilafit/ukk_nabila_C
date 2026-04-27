<!DOCTYPE html>
<html>
<head>
    <title>Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-info">
    <div class="container-fluid">
        <span class="navbar-brand">DASHBOARD PETUGAS PERPUSTAKAAN DIGITAL</span>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h3>Selamat Datang  {{ session('name') }}</h3>

    <div class="card mt-3">
        <div class="card-body text-center">
            <h5>Kelola Peminjaman</h5>
            <a href="/loans" class="btn btn-light">Masuk</a>
        </div>
    </div>
</div>

</body>
</html>
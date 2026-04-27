<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Barang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Sistem Peminjaman</span>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h3>Selamat Datang, {{ session('name') }}</h3>

    {{-- NOTIF --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- SEARCH (HANYA ADMIN & PETUGAS) --}}
    @if(session('role') != 'siswa')
    <form method="GET" action="/loans" class="mb-3 d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="{{ request('search') }}">
        <button class="btn btn-primary">Cari</button>
    </form>
    @endif

    {{-- FORM PINJAM --}}
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Input Peminjaman
        </div>

        <div class="card-body">
            <form method="POST" action="/borrow">
                @csrf

                <div class="mb-3">
                    <label>NIS / ID Siswa</label>
                    <input type="text" name="nis" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Pilih Barang</label>
                    <select name="item_id" class="form-control">
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->name }} (stok: {{ $item->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-success">Pinjam</button>
            </form>
        </div>
    </div>

    {{-- TABEL (HANYA ADMIN & PETUGAS) --}}
    @if(session('role') != 'siswa')
    <div class="card mt-4">
        <div class="card-header bg-success text-white">
            Barang Dipinjamkan
        </div>

        <div class="card-body">
            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Barang</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($loans as $loan)
                @php
                    $tglPinjam = \Carbon\Carbon::parse($loan->borrow_date)->format('d-m-Y');
                    $jatuhTempo = $loan->due_date 
                        ? \Carbon\Carbon::parse($loan->due_date)->format('d-m-Y') 
                        : '-';

                    $telat = $loan->due_date && now()->gt($loan->due_date) && $loan->status == 'dipinjam';

                    // LOGIKA DENDA
                    if ($loan->status == 'dipinjam') {
                        $hari = \Carbon\Carbon::parse($loan->borrow_date)->diffInDays(now());
                        $denda = $hari > 3 ? ($hari - 3) * 5000 : 0;
                    } elseif ($loan->status == 'hilang') {
                        $denda = 50000;
                    } elseif ($loan->status == 'rusak') {
                        $denda = 100000;
                    } else {
                        $denda = 0;
                    }
                @endphp

                <tr>
                    <td>{{ $loan->nama_peminjam }}</td>
                    <td>{{ $loan->item->name }}</td>
                    <td>{{ $tglPinjam }}</td>
                    <td>{{ $jatuhTempo }}</td>

                    {{-- DENDA --}}
                    <td>
                        @if($loan->status == 'kembali')
                            <span class="text-success">Tidak ada</span>
                        @else
                            Rp {{ number_format($denda, 0, ',', '.') }}
                        @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($telat)
                            <span class="badge bg-danger">Terlambat</span>
                        @elseif($loan->status == 'dipinjam')
                            <span class="badge bg-warning">Dipinjam</span>
                        @elseif($loan->status == 'kembali')
                            <span class="badge bg-success">Kembali</span>
                        @elseif($loan->status == 'rusak')
                            <span class="badge bg-dark">Rusak</span>
                        @elseif($loan->status == 'hilang')
                            <span class="badge bg-danger">Hilang</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td>
                        @if($loan->status == 'dipinjam')
                            <a href="/return/{{ $loan->id }}" class="btn btn-success btn-sm">Kembali</a>
                            <a href="/rusak/{{ $loan->id }}" class="btn btn-dark btn-sm">Rusak</a>
                            <a href="/hilang/{{ $loan->id }}" class="btn btn-danger btn-sm">Hilang</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>
    @endif

</div>

</body>
</html>
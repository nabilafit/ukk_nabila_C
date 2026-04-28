<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        #result {
            z-index: 999;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
    <style>
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Peminjaman Perpustakaan Digital</span>

        <div class="d-flex gap-2">
            <a href="
                @if(session('role') == 'admin') /admin
                @elseif(session('role') == 'petugas') /petugas
                @else /siswa
                @endif
            " class="btn btn-light btn-sm">Kembali</a>

            <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4 col-lg-10 mx-auto">

    <h3 class="mb-3">Selamat Datang, {{ session('name') }}</h3>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- HITUNG DENDA --}}
    @php
        $lateCount = $loans->where('is_late', true)->count();
        $totalDenda = $loans->sum('final_denda');
    @endphp

    @if($lateCount > 0)
        <div class="alert alert-danger">
            Kamu punya {{ $lateCount }} buku terlambat dikembalikan
        </div>
    @endif

    @if($totalDenda > 0)
        <div class="alert alert-warning">
            Total denda: Rp {{ number_format($totalDenda,0,',','.') }}
        </div>
    @endif

    {{-- SEARCH --}}
    <div class="position-relative mb-3">
        <input type="text" id="search" class="form-control" placeholder="Cari buku...">
        <div id="result" class="list-group position-absolute w-100 shadow" style="display:none;"></div>
    </div>

    {{-- FORM PINJAM --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Input Peminjaman
        </div>

        <div class="card-body">
            <form method="POST" action="/borrow">
                @csrf

                <div class="row g-2 align-items-end">

                    <div class="col-md-4">
                        <label>NIS / ID</label>
                        <input type="text" name="nis" class="form-control" required>
                    </div>

                    <div class="col-md-5">
                        <label>Pilih Buku</label>
                        <select name="item_id" id="itemSelect" class="form-control">
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} (stok: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Jumlah</label>
                        <input type="number" name="jumlah" class="form-control text-center" min="1" value="1">
                    </div>

                    <div class="col-md-1 d-grid">
                        <button class="btn btn-secondary">Pinjam</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- DAFTAR --}}
    <div class="card shadow mb-4">
        <div class="card-header bg-success text-white">
            Daftar Buku Tersedia
        </div>

        <div class="card-body">
            <div class="row">
                @foreach($items as $item)
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">

                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}"
                                 class="card-img-top"
                                 style="height:120px;object-fit:cover;">
                        @endif

                        <div class="card-body">
                            <h6>{{ $item->name }}</h6>
                            <p>Stok: {{ $item->stock }}</p>
                        </div>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    @if(session('role') != 'siswa')
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            Data Peminjaman
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Buku</th>
                        <th>Tanggal</th>
                        <th>Tempo</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($loans as $loan)

                @php
                    $tgl = \Carbon\Carbon::parse($loan->borrow_date)->translatedFormat('l, d F Y');
                    $tempo = $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->translatedFormat('l, d F Y') : '-';
                    $denda = $loan->final_denda;
                @endphp

                <tr>
                    <td>{{ $loan->nama_peminjam }}</td>
                    <td>{{ $loan->item->name ?? '-' }}</td>
                    <td>{{ $tgl }}</td>
                    <td>{{ $tempo }}</td>

                    {{-- DENDA --}}
                    <td class="text-right">
                       @if($denda > 0)
                            Rp {{ number_format($denda,0,',','.') }}
                       @else
                       <span class="text-success">Tidak ada</span>
                       @endif
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($loan->is_late)
                            <span class="badge bg-danger">Terlambat</span>
                        @elseif($loan->status == 'dipinjam')
                            <span class="badge bg-secondary">Dipinjam</span>
                        @elseif($loan->status == 'kembali')
                            <span class="badge bg-success">Kembali</span>
                        @elseif($loan->status == 'rusak')
                            <span class="badge bg-warning">Rusak</span>
                        @elseif($loan->status == 'hilang')
                            <span class="badge bg-danger">Hilang</span>
                        @elseif($loan->status == 'lunas')
                            <span class="badge bg-primary">Lunas</span>
                        @endif
                    </td>

                    {{-- PEMBAYARAN --}}
                    @php
    $denda = $loan->final_denda ?? $loan->denda;
@endphp

<td>
    @if($denda > 0)
        @if($loan->is_paid)
            <span class="badge bg-success">LUNAS</span>
        @else
            <span class="text-danger">
                Rp {{ number_format($denda,0,',','.') }}
            </span>

            <a href="/bayar/{{ $loan->id }}" class="btn btn-sm btn-primary">
                Bayar
            </a>
        @endif
    @else
        <span class="text-muted">-</span>
    @endif
</td>

                    {{-- AKSI --}}
                    <td>
                        @if($loan->status == 'dipinjam')
                            <a href="/return/{{ $loan->id }}" class="btn btn-success btn-sm">Kembali</a>
                            <a href="/rusak/{{ $loan->id }}" class="btn btn-warning btn-sm">Rusak</a>
                            <a href="/hilang/{{ $loan->id }}" class="btn btn-danger btn-sm">Hilang</a>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>

                </tr>

                @empty
                <tr>
                    <td colspan="8">Belum ada data</td>
                </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>
    @endif

</div>

{{-- SEARCH JS --}}
<script>
const searchInput = document.getElementById('search');
const resultBox = document.getElementById('result');
const selectItem = document.getElementById('itemSelect');

searchInput.addEventListener('input', function () {
    let query = this.value.trim();

    if (!query) {
        resultBox.style.display = 'none';
        return;
    }

    fetch(`/search-items?search=${query}`)
        .then(res => res.json())
        .then(data => {
            let html = '';

            data.forEach(item => {
                html += `<div class="list-group-item pilih-item" data-id="${item.id}">
                    ${item.name}
                </div>`;
            });

            resultBox.innerHTML = html || `<div class="list-group-item">Tidak ditemukan</div>`;
            resultBox.style.display = 'block';
        });
});

document.addEventListener('click', function(e){
    if(e.target.classList.contains('pilih-item')){
        selectItem.value = e.target.dataset.id;
        searchInput.value = e.target.innerText;
        resultBox.style.display = 'none';
    }
});

// auto hide alert
setTimeout(() => {
    document.querySelectorAll('.alert').forEach(el => el.remove());
}, 3000);
</script>

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-info">
    <div class="container-fluid">
        <span class="navbar-brand">Dashboard Peminjaman Perpustakaan Digital</span>
        <a href="/logout" class="btn btn-danger btn-sm">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h3>Selamat Datang, {{ session('name') }}</h3>

    {{-- HITUNG DATA --}}
    @php
        $lateCount = $loans->filter(function($loan){
            return ($loan->status == 'dipinjam' && $loan->due_date && \Carbon\Carbon::parse($loan->due_date)->isPast());
        })->count();

        $totalDenda = $loans->sum('denda');
    @endphp

    {{-- NOTIF TELAT --}}
    @if($lateCount > 0)
        <div class="alert alert-danger">
             Kamu punya {{ $lateCount }} buku terlambat!
        </div>
    @endif

    @if($totalDenda > 0)
        <div class="alert alert-warning">
            Total denda kamu: Rp {{ number_format($totalDenda,0,',','.') }}
        </div>
    @endif

    {{-- RIWAYAT PINJAMAN --}}
    <div class="card mt-4">
        <div class="card-header bg-dark text-white">
            Riwayat Peminjaman
        </div>

        <div class="card-body">
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Status</th>
                        <th>Denda</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($loans as $loan)
                <tr>

                    {{-- BUKU --}}
                    <td>
                        {{ $loan->item->name ?? '-' }}
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @php
                            $isLate = ($loan->status == 'dipinjam' && $loan->due_date && \Carbon\Carbon::parse($loan->due_date)->isPast());
                        @endphp

                        @if($loan->is_paid)
                            <span class="badge bg-success">LUNAS</span>

                        @elseif($loan->status == 'hilang')
                            <span class="badge bg-danger">HILANG</span>

                        @elseif($loan->status == 'rusak')
                            <span class="badge bg-warning">RUSAK</span>

                        @elseif($isLate)
                            <span class="badge bg-danger">TELAT</span>

                        @elseif($loan->status == 'dipinjam')
                            <span class="badge bg-secondary">Dipinjam</span>

                        @else
                            <span class="badge bg-dark">{{ $loan->status }}</span>
                        @endif
                    </td>

                    {{-- DENDA --}}
                    <td>
                        @php
                            $denda = $loan->denda ?? 0;
                        @endphp

                        Rp {{ number_format($denda,0,',','.') }}

                        @if($loan->is_paid)
                            <span class="badge bg-success">LUNAS</span>
                        @endif
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="3">Belum ada data peminjaman</td>
                </tr>
                @endforelse

                </tbody>

            </table>
        </div>
    </div>

</div>

</body>
</html>
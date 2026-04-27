<form action="/borrow" method="POST">
    @csrf

    <input type="hidden" name="item_id" value="{{ $item->id }}">

    <div class="mb-2">
        <label>Nama Siswa</label>
        <input type="text" name="nama_peminjam" class="form-control" placeholder="Masukkan nama siswa" required>
    </div>

    <div class="mb-2">
        <label>Tanggal Pinjam</label>
        <input type="date" name="borrow_date" class="form-control" required>
    </div>

    <button class="btn btn-primary">Simpan Peminjaman</button>
</form>
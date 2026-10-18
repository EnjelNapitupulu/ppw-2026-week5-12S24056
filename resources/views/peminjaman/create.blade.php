<x-layout title="Pinjam Buku">
    <h3>Pinjam Buku</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Buku</label>
            <select name="buku_id" class="form-select @error('buku_id') is-invalid @enderror">
                <option value="">-- Pilih Buku --</option>
                @foreach($bukus as $b)
                    <option value="{{ $b->id }}" @selected(old('buku_id') == $b->id)>
                        {{ $b->judul }} (stok: {{ $b->stok }})
                    </option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">NIM Peminjam</label>
                <input type="text" name="nim_peminjam" value="{{ old('nim_peminjam') }}"
                       class="form-control @error('nim_peminjam') is-invalid @enderror">
            </div>
            <div class="col-md-5 mb-3">
                <label class="form-label">Nama Peminjam</label>
                <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}"
                       class="form-control @error('nama_peminjam') is-invalid @enderror">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Lama Pinjam (hari)</label>
                <input type="number" name="lama_pinjam" value="{{ old('lama_pinjam', 7) }}"
                       class="form-control @error('lama_pinjam') is-invalid @enderror">
            </div>
        </div>
        <button class="btn btn-del">Pinjam</button>
        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</x-layout>
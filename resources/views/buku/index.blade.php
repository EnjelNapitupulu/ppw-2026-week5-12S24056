<x-layout title="Daftar Buku">
    {{-- Kartu statistik (DB::table) --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <div class="text-muted small">Total Judul Buku</div>
                <div class="fs-3 fw-bold">{{ $stats['total_judul'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <div class="text-muted small">Total Stok Tersedia</div>
                <div class="fs-3 fw-bold">{{ $stats['total_stok'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <div class="text-muted small">Sedang Dipinjam</div>
                <div class="fs-3 fw-bold text-warning">{{ $stats['total_dipinjam'] }}</div>
            </div></div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-0"><div class="card-body">
                <div class="text-muted small">Total Dikembalikan</div>
                <div class="fs-3 fw-bold text-success">{{ $stats['total_dikembalikan'] }}</div>
            </div></div>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Buku</h3>
        @auth
            <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku</a>
        @endauth
    </div>

    {{-- Form pencarian (GET, tidak perlu @csrf) --}}
    <form method="GET" action="{{ route('buku.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-control" placeholder="Cari judul, penulis, atau ISBN...">
        </div>
        <div class="col-md-4">
            <select name="kategori_id" class="form-select">
                <option value="">Semua kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" @selected(request('kategori_id') == $k->id)>{{ $k->nama_kategori }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-del">Cari</button>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped bg-white shadow-sm align-middle">
            <thead>
                <tr>
                    <th>ISBN</th><th>Judul</th><th>Penulis</th><th>Kategori</th>
                    <th>Stok</th><th>Dipinjam (kali)</th>
                    @auth<th>Aksi</th>@endauth
                </tr>
            </thead>
            <tbody>
            @forelse($bukus as $buku)
                <tr>
                    <td>{{ $buku->isbn }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->kategori->nama_kategori }}</td>
                    <td>{{ $buku->stok }}</td>
                    <td>{{ $buku->peminjamans_count }}</td>
                    @auth
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('buku.show', $buku) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('buku.destroy', $buku) }}" method="POST"
                                  onsubmit="return confirm('Hapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                    @endauth
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted">Tidak ada buku yang cocok.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $bukus->links() }}
</x-layout>
<x-layout title="Daftar Kategori">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Kategori</h3>
        <a href="{{ route('kategori.create') }}" class="btn btn-del">+ Tambah Kategori</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped bg-white shadow-sm align-middle">
            <thead>
                <tr><th>Kode</th><th>Nama Kategori</th><th>Jumlah Buku</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($kategoris as $kategori)
                <tr>
                    <td>{{ $kategori->kode_kategori }}</td>
                    <td>{{ $kategori->nama_kategori }}</td>
                    <td>{{ $kategori->bukus_count }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('kategori.show', $kategori) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST"
                                  onsubmit="return confirm('Hapus kategori ini? Semua buku di dalamnya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">Belum ada data kategori.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $kategoris->links() }}
</x-layout>
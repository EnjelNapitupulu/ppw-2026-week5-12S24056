<x-layout title="Daftar Buku">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Buku</h3>
        <a href="{{ route('buku.create') }}" class="btn btn-del">+ Tambah Buku</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped bg-white shadow-sm align-middle">
            <thead>
                <tr><th>ISBN</th><th>Judul</th><th>Penulis</th><th>Kategori</th><th>Stok</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($bukus as $buku)
                <tr>
                    <td>{{ $buku->isbn }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->kategori->nama_kategori }}</td>
                    <td>{{ $buku->stok }}</td>
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
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Belum ada data buku.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $bukus->links() }}
</x-layout>
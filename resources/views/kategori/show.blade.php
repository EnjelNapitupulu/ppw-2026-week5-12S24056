<x-layout title="Detail Kategori">
    <h3>Detail Kategori</h3>
    <table class="table bg-white shadow-sm">
        <tr><th style="width:200px">Kode</th><td>{{ $kategori->kode_kategori }}</td></tr>
        <tr><th>Nama</th><td>{{ $kategori->nama_kategori }}</td></tr>
    </table>

    <h5 class="mt-4">Buku dalam kategori ini</h5>
    <ul class="list-group mb-3">
        @forelse($kategori->bukus as $buku)
            <li class="list-group-item">
                <a href="{{ route('buku.show', $buku) }}">{{ $buku->judul }}</a>
            </li>
        @empty
            <li class="list-group-item text-muted">Belum ada buku.</li>
        @endforelse
    </ul>

    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning">Edit</a>
</x-layout>
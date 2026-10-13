<x-layout title="Detail Buku">
    <h3>Detail Buku</h3>
    <table class="table bg-white shadow-sm">
        <tr><th style="width:200px">ISBN</th><td>{{ $buku->isbn }}</td></tr>
        <tr><th>Judul</th><td>{{ $buku->judul }}</td></tr>
        <tr><th>Penulis</th><td>{{ $buku->penulis }}</td></tr>
        <tr><th>Penerbit</th><td>{{ $buku->penerbit }}</td></tr>
        <tr><th>Tahun Terbit</th><td>{{ $buku->tahun_terbit }}</td></tr>
        <tr><th>Kategori</th><td>{{ $buku->kategori->nama_kategori }}</td></tr>
        <tr><th>Stok</th><td>{{ $buku->stok }}</td></tr>
        <tr><th>Sinopsis</th><td>{{ $buku->sinopsis ?: '-' }}</td></tr>
    </table>
    <a href="{{ route('buku.index') }}" class="btn btn-secondary">Kembali</a>
    <a href="{{ route('buku.edit', $buku) }}" class="btn btn-warning">Edit</a>
</x-layout>
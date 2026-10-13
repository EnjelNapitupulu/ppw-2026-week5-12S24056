<x-layout title="Tambah Buku">
    <h3>Tambah Buku</h3>
    <form action="{{ route('buku.store') }}" method="POST">
        @csrf
        @include('buku._form', ['buku' => null])
        <button class="btn btn-del">Simpan</button>
        <a href="{{ route('buku.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</x-layout>
<x-layout title="Tambah Kategori">
    <h3>Tambah Kategori</h3>
    <form action="{{ route('kategori.store') }}" method="POST">
        @csrf
        @include('kategori._form', ['kategori' => null])
        <button class="btn btn-del">Simpan</button>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</x-layout>
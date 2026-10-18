<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    private function rules(?Buku $buku = null): array
    {
        return [
            'isbn' => ['required', 'digits:13', Rule::unique('bukus', 'isbn')->ignore($buku?->id)],
            'judul' => ['required', 'string', 'min:5', 'max:200'],
            'penulis' => ['required', 'string', 'max:150'],
            'penerbit' => ['required', 'string', 'max:100'],
            'tahun_terbit' => ['required', 'integer', 'between:1900,' . date('Y')],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'stok' => ['required', 'integer', 'min:0'],
            'sinopsis' => ['nullable', 'string'],
        ];
    }

    public function index(Request $request)
{
    // Simpan histori pencarian terakhir di session (dipakai di Bagian 4)
    if ($request->filled('search')) {
        session(['last_search' => $request->search]);
    }

    $bukus = Buku::with('kategori')
        ->withCount('peminjamans')
        ->when($request->search, function ($q, $s) {
            $q->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('penulis', 'like', "%{$s}%")
                  ->orWhere('isbn', 'like', "%{$s}%");
            });
        })
        ->when($request->kategori_id, fn ($q, $k) => $q->where('kategori_id', $k))
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $stats = [
        'total_judul' => DB::table('bukus')->count(),
        'total_stok' => DB::table('bukus')->sum('stok'),
        'total_dipinjam' => DB::table('peminjamans')->where('status', 'dipinjam')->count(),
        'total_dikembalikan' => DB::table('peminjamans')->where('status', 'dikembalikan')->count(),
    ];

    $kategoris = Kategori::orderBy('nama_kategori')->get();

    return view('buku.index', compact('bukus', 'stats', 'kategoris'));
}

    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        Buku::create($request->validate($this->rules()));
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('buku.edit', compact('buku', 'kategoris'));
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($request->validate($this->rules($buku)));
        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}
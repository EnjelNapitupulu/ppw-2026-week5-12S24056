<x-layout title="Daftar Peminjaman">
    <div class="d-flex justify-content-between mb-3">
        <h3>Daftar Peminjaman</h3>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-del">+ Pinjam Buku</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped bg-white shadow-sm align-middle">
            <thead>
                <tr><th>Kode</th><th>Buku</th><th>Peminjam</th><th>Pinjam</th><th>Jatuh Tempo</th><th>Kembali</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            @forelse($peminjamans as $p)
                <tr>
                    <td>{{ $p->kode_pinjam }}</td>
                    <td>{{ $p->buku->judul }}</td>
                    <td>{{ $p->nama_peminjam }}<br><small class="text-muted">{{ $p->nim_peminjam }}</small></td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_jatuh_tempo->format('d/m/Y') }}</td>
                    <td>{{ $p->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $p->status === 'dipinjam' ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ $p->status }}
                        </span>
                    </td>
                    <td>
                        @if($p->status === 'dipinjam')
                            <form action="{{ route('peminjaman.kembali', $p) }}" method="POST"
                                  onsubmit="return confirm('Kembalikan buku ini?')">
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-sm btn-success">Kembalikan</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">Belum ada data peminjaman.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $peminjamans->links() }}
</x-layout>
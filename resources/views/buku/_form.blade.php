@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach([['isbn', 'ISBN (13 digit)'], ['judul', 'Judul'], ['penulis', 'Penulis'], ['penerbit', 'Penerbit']] as [$f, $label])
    <div class="mb-3">
        <label class="form-label">{{ $label }}</label>
        <input type="text" name="{{ $f }}" value="{{ old($f, $buku?->$f) }}"
               class="form-control @error($f) is-invalid @enderror">
        @error($f)<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
@endforeach

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku?->tahun_terbit) }}"
               class="form-control @error('tahun_terbit') is-invalid @enderror">
        @error('tahun_terbit')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Kategori</label>
        <select name="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih --</option>
            @foreach($kategoris as $k)
                <option value="{{ $k->id }}" @selected(old('kategori_id', $buku?->kategori_id) == $k->id)>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stok" value="{{ old('stok', $buku?->stok ?? 0) }}"
               class="form-control @error('stok') is-invalid @enderror">
        @error('stok')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Sinopsis</label>
    <textarea name="sinopsis" rows="4" class="form-control">{{ old('sinopsis', $buku?->sinopsis) }}</textarea>
</div>
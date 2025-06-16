@extends('layouts.default')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Kategori</h4>
        </div>
        <div class="card-body">
            <form action="{{ isset($kategori) ? route('kategori.update', $kategori->id) : route('kategori.store') }}" method="POST">
                @csrf
                @if(isset($kategori))
                @method('PUT')
                @endif

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama" class="form-control" required
                        value="{{ old('nama', $kategori->nama ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Tipe</label>
                    <select name="tipe" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="pemasukan" {{ (old('tipe', $kategori->tipe ?? '') == 'pemasukan') ? 'selected' : '' }}>Pemasukan</option>
                        <option value="pengeluaran" {{ (old('tipe', $kategori->tipe ?? '') == 'pengeluaran') ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">
                    {{ isset($kategori) ? 'Update' : 'Simpan' }}
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
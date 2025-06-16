@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($kategori) ? 'Edit Kategori' : 'Create New Kategori' }}</h4>
                    <form action="{{ isset($kategori) ? route('kategori.update', $kategori->id) : route('kategori.store') }}" method="POST" class="form-horizontal mt-4">
                        @csrf
                        @if(isset($kategori))
                            @method('PUT')
                        @endif

                        <div class="form-group row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Kategori</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                                    value="{{ old('nama', $kategori->nama ?? '') }}" required>
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="tipe" class="col-sm-2 col-form-label">Tipe</label>
                            <div class="col-sm-10">
                                <select name="tipe" id="tipe" class="form-control @error('tipe') is-invalid @enderror" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="pemasukan" {{ (old('tipe', $kategori->tipe ?? '') == 'pemasukan') ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="pengeluaran" {{ (old('tipe', $kategori->tipe ?? '') == 'pengeluaran') ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                                @error('tipe')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($kategori) ? 'Update Kategori' : 'Create Kategori' }}
                                </button>
                                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
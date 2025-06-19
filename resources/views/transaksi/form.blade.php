@extends('layouts.default')

@section('title', isset($transaksi) && $transaksi->exists ? 'Edit User' : 'Add New User')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-12 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">
                {{ isset($transaksi) && $transaksi->exists ? 'Edit Transaksi' : 'Add New Transaksi' }}
            </h4>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card rounded-3 shadow-sm">
                <div class="card-body">
                    <form
                        action="{{ isset($transaksi) && $transaksi->exists ? route('transaksi.update', $transaksi->id) : route('transaksi.store') }}"
                        method="POST">
                        @csrf
                        @if(isset($transaksi) && $transaksi->exists)
                        @method('PUT')
                        @endif

                        <div class="mb-3">
                            <label for="dompet_id" class="form-label">Dompet <span class="text-danger">*</span></label>
                            <select class="form-select @error('dompet_id') is-invalid @enderror" id="dompet_id" name="dompet_id" required>
                                <option value="">Select Dompet</option>
                                @foreach ($dompets as $dompet)
                                <option value="{{ $dompet->id }}" {{ old('dompet_id', $transaksi->dompet_id ?? '') === $dompet->id ? 'selected' : '' }}>
                                    {{ $dompet->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('dompet_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori_id" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                                <option value="">Select Kategori</option>
                                @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id', $transaksi->kategori_id ?? '') === $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                name="nominal" value="{{ old('nominal', $transaksi->nominal ?? '') }}" required>
                            @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan"
                                name="keterangan" value="{{ old('keterangan', $transaksi->keterangan ?? '') }}" required>
                            @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('transaksi.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($transaksi) && $transaksi->exists ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
</div>
@endsection
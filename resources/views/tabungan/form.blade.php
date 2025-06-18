@extends('layouts.default')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($tabungan) ? 'Edit Tabungan' : 'Create New Tabungan' }}</h4>
                    <form action="{{ isset($tabungan) ? route('tabungan.update', $tabungan->id) : route('tabungan.store') }}" method="POST" class="form-horizontal mt-4">
                        @csrf
                        @if(isset($tabungan))
                            @method('PUT')
                        @endif

                        <div class="form-group row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ isset($tabungan) ? $tabungan->nama : old('nama') }}" required>
                                @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="saldo" class="col-sm-2 col-form-label">Saldo</label>
                            <div class="col-sm-10">
                                <input type="number" name="saldo" id="saldo" class="form-control @error('saldo') is-invalid @enderror"
                                    value="{{ isset($tabungan) ? $tabungan->saldo : old('saldo') }}" min="0" step="0.01" required>
                                @error('saldo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="target" class="col-sm-2 col-form-label">Target</label>
                            <div class="col-sm-10">
                                <input type="number" name="target" id="target" class="form-control @error('target') is-invalid @enderror"
                                    value="{{ isset($tabungan) ? $tabungan->target : old('target') }}" min="0" step="0.01" required>
                                @error('target')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10 offset-sm-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ isset($tabungan) ? 'Update Tabungan' : 'Create Tabungan' }}
                                </button>
                                <a href="{{ route('tabungan.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
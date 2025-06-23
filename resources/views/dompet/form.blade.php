@extends('layouts.default')

@section('title', isset($dompet) ? 'Edit Wallet' : 'Add New Wallet')

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-12 align-self-center">
                <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">
                    {{ isset($dompet) ? 'Edit Wallet' : 'Add New Wallet' }}
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
                            action="{{ isset($dompet) ? route('dompet.update', $dompet->id) : route('dompet.store') }}"
                            method="POST">
                            @csrf
                            @if(isset($dompet))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label for="nama" class="form-label">Wallet Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                    name="nama" value="{{ old('nama', $dompet->nama ?? '') }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="saldo" class="form-label">Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('saldo') is-invalid @enderror" id="saldo"
                                        name="saldo" value="{{ old('saldo', $dompet->saldo ?? 0) }}" step="0.01">
                                </div>
                                @error('saldo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('dompet.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ isset($dompet) ? 'Update' : 'Save' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
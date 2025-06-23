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
                            <label for="saldoFormatted" class="col-sm-2 col-form-label">Saldo</label>
                            <div class="col-sm-10">
                                <input type="text" id="saldoFormatted" class="form-control format-saldo @error('saldo') is-invalid @enderror"
                                    value="{{ isset($tabungan) ? number_format($tabungan->saldo, 0, ',', '.') : old('saldo') }}" required>
                                <input type="hidden" name="saldo" id="saldo"
                                    value="{{ isset($tabungan) ? $tabungan->saldo : old('saldo') }}">
                                @error('saldo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="targetFormatted" class="col-sm-2 col-form-label">Target</label>
                            <div class="col-sm-10">
                                <input type="text" id="targetFormatted" class="form-control format-saldo @error('target') is-invalid @enderror"
                                    value="{{ isset($tabungan) ? number_format($tabungan->target, 0, ',', '.') : old('target') }}" required>
                                <input type="hidden" name="target" id="target"
                                    value="{{ isset($tabungan) ? $tabungan->target : old('target') }}">
                                @error('target')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="dompet_id" class="col-sm-2 col-form-label">Awal Saldo Ambil dari</label>
                            <div class="col-sm-10">
                                <select name="dompet_id" id="dompet_id" class="form-control @error('dompet_id') is-invalid @enderror" required>
                                    <option value="">Pilih Dompet</option>
                                    @foreach($dompets as $dompet)
                                    <option value="{{ $dompet->id }}" {{ isset($tabungan) && $tabungan->dompet_id == $dompet->id ? 'selected' : '' }}>
                                        {{ $dompet->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('dompet_id')
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const formatInputs = document.querySelectorAll('.format-saldo');

        formatInputs.forEach(input => {
            input.addEventListener('input', function() {
                let value = this.value.replace(/\D/g, '');
                if (value) {
                    this.value = parseInt(value).toLocaleString('id-ID');
                } else {
                    this.value = '';
                }

                // Update hidden input
                const targetHiddenId = this.id.replace('Formatted', '');
                const hiddenInput = document.getElementById(targetHiddenId);
                if (hiddenInput) {
                    hiddenInput.value = value;
                }
            });
        });
    });
</script>

@endsection
@extends('layouts.default')

@section('style')
<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8f9fa;
        /* Light gray background */
    }

    .card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .table-custom {
        border-radius: 1rem;
        overflow: hidden;
        /* Ensures the border-radius is applied to the table */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        border-radius: 0.5rem;
    }
</style>
@endsection
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-10 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Transaksi Tabungan</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Transaksi Tabungan</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <h4 class="card-title">Transaksi Tabungan List</h4>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tabungan</th>
                                    <th>Dompet</th>
                                    <th>Tipe</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transaksis as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $trx->tabungan->nama ?? 'N/A' }}</td>
                                    <td>{{ $trx->dompet->nama ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge px-3 py-2 rounded-pill 
                                        {{ $trx->tipe === 'deposit' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ ucfirst($trx->tipe) }}
                                        </span>
                                    </td>
                                    <td>Rp {{ number_format($trx->nominal, 2, ',', '.') }}</td>
                                    <td>{{ $trx->keterangan }}</td>
                                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No tabungan transactions found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
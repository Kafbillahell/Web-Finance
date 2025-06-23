@extends('layouts.default')

{{-- Debug output --}}
@php
// Uncomment the line below to debug transactions data
// dd($transactions);
@endphp

@section('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

    .header-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .uppercase {
        text-transform: capitalize;
    }

    /* Styles for the information and statistics cards */
    .info-stats-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.08);
        height: 100%;
        /* Ensure cards take full height of column */
    }

    .info-stats-card .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #343a40;
    }

    .info-stats-card .form-label {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
        display: block;
    }

    .info-stats-card .form-control-plaintext {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
        padding-bottom: 0.5rem;
        border-bottom: 1px dashed #e9ecef;
        margin-bottom: 1rem;
    }

    .info-stats-card .form-control-plaintext:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .stats-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .stats-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Styles for the transaction history table */
    .transaction-history-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .transaction-history-card .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #343a40;
    }

    .table-responsive {
        margin-top: 1rem;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
        border-bottom: 2px solid #e9ecef;
        padding: 0.75rem;
    }

    .table td {
        font-size: 0.9rem;
        color: #495057;
        padding: 0.75rem;
        vertical-align: middle;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.4em 0.7em;
        border-radius: 0.5rem;
        font-weight: 600;
    }

    .bg-success {
        background-color: #28a745 !important;
        color: white !important;
    }

    .bg-danger {
        background-color: #dc3545 !important;
        color: white !important;
    }

    .text-success {
        color: #28a745 !important;
    }

    .text-danger {
        color: #dc3545 !important;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .text-center {
        text-align: center;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
</style>
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-10 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Detail Dompet: {{ $dompet->nama }}</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item" aria-current="page">Home</li>
                        <li class="breadcrumb-item" aria-current="page">Dompet</li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $dompet->nama }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-2 align-self-center text-end">
            <a href="{{ route('dompet.index') }}" class="btn btn-secondary btn-rounded">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row mb-4 g-4">
        <div class="col-md-4">
            <div class="card info-stats-card">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Dompet</span>
                        <span class="badge bg-primary bg-opacity-10 text-primary">{{ $dompet->nama }}</span>
                    </div>
                    <h4 class="mb-0 text-success">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h4>
                    <span class="text-muted small">Saldo Tersedia</span>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card info-stats-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Transaksi</span>
                                <i class="fas fa-exchange-alt text-primary"></i>
                            </div>
                            <h4 class="mb-0 text-primary">{{ $transactions->count() }}</h4>
                            <span class="text-muted small">Total Transaksi</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card info-stats-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Pemasukan</span>
                                <i class="fas fa-arrow-up text-success"></i>
                            </div>
                            <h4 class="mb-0 text-success">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</h4>
                            <span class="text-muted small">Total Pemasukan</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card info-stats-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted small">Pengeluaran</span>
                                <i class="fas fa-arrow-down text-danger"></i>
                            </div>
                            <h4 class="mb-0 text-danger">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</h4>
                            <span class="text-muted small">Total Pengeluaran</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card transaction-history-card">
                <div class="card-body">
                    <h5 class="card-title">Riwayat Transaksi</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transaction->kategori->nama ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $transaction->kategori->tipe === 'pemasukan' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($transaction->kategori->tipe) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="fw-bold {{ $transaction->kategori->tipe === 'pemasukan' ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>{{ $transaction->keterangan }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <i class="fas fa-exchange-alt display-4 mb-3 text-muted"></i>
                                        <p class="mb-0">Belum ada transaksi untuk dompet ini.</p>
                                    </td>
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
@extends('layouts.default')

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

    .table-custom {
        border-radius: 1rem;
        overflow: hidden;
        /* Ensures the border-radius is applied to the table */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }

    .transaction-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .icon-income {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }

    .icon-expense {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
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
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Transaksi Management</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item" aria-current="page">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Transaksi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-2 align-self-center text-end">
            <a href="{{ route('transaksi.create') }}" class="btn btn-primary btn-rounded" id="add-user-btn">Add Transaksi</a>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row mb-4 g-4">
        <div class="col-md-6">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-2 text-muted">Total Income</p>
                        <h2 class="display-6 fw-bold text-success">Rp {{ $formatted_income }}</h2>
                    </div>
                    <div class="transaction-icon icon-income">
                        <i class="bi bi-arrow-down"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-4 h-100">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <p class="mb-2 text-muted">Total Expense</p>
                        <h2 class="display-6 fw-bold text-danger">Rp {{ $formatted_expense }}</h2>
                    </div>
                    <div class="transaction-icon icon-expense">
                        <i class="bi bi-arrow-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
                    </div>
                    @endif

                    <h4 class="card-title">Transaksi List</h4>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Kategori</th>
                                    <th>Tipe</th>
                                    <th>Nominal</th>
                                    <th>Keterangan</th>
                                    <th>Dompet</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $trx)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $trx->kategori->nama ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge px-3 py-2 rounded-pill 
                                             {{ $trx->kategori && $trx->kategori->tipe === 'pemasukan' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                                            {{ ucfirst($trx->kategori->tipe ?? '-') }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($trx->nominal, 2, ',', '.') }}</td>
                                    <td>{{ $trx->keterangan }}</td>
                                    <td>{{ $trx->dompet->nama ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('transaksi.edit', $trx->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('transaksi.destroy', $trx->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No transactions found.</td>
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
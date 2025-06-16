@extends('layouts.default')
@section('style')
<style>
    .savings-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .savings-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .progress {
        height: 0.75rem;
        border-radius: 0.75rem;
    }

    .progress-bar {
        background: linear-gradient(45deg, #0d6efd, #6f42c1);
        border-radius: 0.75rem;
    }

    .amount {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .currency-symbol {
        color: #6c757d;
    }

    .btn-action {
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }

    .i_tbng {
        margin-right: 1rem;
        font-size: 1.25rem;
    }

    /* .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.5rem 1rem;
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
    } */
</style>
@endsection
@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Tabungan</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Tabungan</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="col-5 align-self-center">
            <div class="d-flex justify-content-between">
                <div>
                    <a href="{{ route('tabungan.create') }}" class="btn btn-primary btn-rounded me-2">
                        <i class="fas fa-plus me-1"></i> Add Tabungan
                    </a>
                </div>
                <div>
                    <form class="d-flex" action="{{ route('tabungan.index') }}" method="GET">
                        <input class="form-control me-2" type="search" name="search" placeholder="Search tabungan...">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        @foreach ($tabungans as $tabungan)
        <div class="col-12 mb-4">
            <div class="savings-card p-4 shadow rounded bg-white">
                <div class="d-flex align-items-center mb-3">
                    <i class="i_tbng fas fa-piggy-bank text-success"></i>
                    <h4 class="mb-0 fw-bold">{{ $tabungan->nama ?? 'Unnamed Goal' }}</h4>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Saldo</small>
                        <small class="fw-semibold">Rp {{ number_format($tabungan->saldo, 2, ',', '.') }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Target</small>
                        <small class="fw-semibold">Rp {{ number_format($tabungan->target, 2, ',', '.') }}</small>
                    </div>

                    @php
                    $progress = $tabungan->target > 0 ? min(100, ($tabungan->saldo / $tabungan->target) * 100) : 0;
                    @endphp
                    <div class="progress mt-2" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $progress }}%;"
                            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between small text-muted mb-3">
                    <div>Dibuat pada: {{ $tabungan->created_at->format('d M Y') }}</div>
                    <div>Created by: {{ $tabungan->user->name ?? 'Unknown' }}</div>
                </div>

                <div class="text-end">
                    <a href="{{ route('tabungan.edit', $tabungan->id) }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                    <form action="{{ route('tabungan.destroy', $tabungan->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <!-- `
        <div class="mt-4">
            {{ $tabungans->links() }}
        </div>` -->
</div>

@endsection
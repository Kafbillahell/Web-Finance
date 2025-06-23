@extends('layouts.default')

@section('title', 'Wallet Management')

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

    .wallet-card {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
        color: white;
        border-radius: 1.2rem;
        padding: 0.75rem;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .wallet-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }

    .wallet-card-blue {
        background: linear-gradient(135deg, #0984e3 0%, #74b9ff 100%);
    }

    .wallet-card-green {
        background: linear-gradient(135deg, #00b894 0%, #55efc4 100%);
    }

    .wallet-card-orange {
        background: linear-gradient(135deg, #e17055 0%, #fab1a0 100%);
    }

    .wallet-card-pink {
        background: linear-gradient(135deg, #fd79a8 0%, #fdcb6e 100%);
    }

    .wallet-balance {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .wallet-name {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.9;
    }

    .stats-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        text-align: center;
        border: 1px solid rgba(0, 0, 0, 0.08);
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

    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.3);
    }

    .action-buttons .btn {
        margin-right: 0.25rem;
        border-radius: 0.5rem;
        padding: 0.375rem 0.75rem;
    }

    .wallet-navigation {
        height: calc(100vh - 100px);
        overflow-y: auto;
        padding-right: 15px;
    }

    .main-content {
        height: calc(100vh - 100px);
        overflow-y: auto;
    }
</style>
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-10 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Wallet Management</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item" aria-current="page">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Wallet</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="wallet-navigation">
                <div class="row g-3">
                    @php
                    $cardClasses = ['wallet-card', 'wallet-card wallet-card-blue', 'wallet-card wallet-card-green', 'wallet-card wallet-card-orange', 'wallet-card wallet-card-pink'];
                    @endphp
                    @forelse($dompet_terpilih ?? [] as $index => $dompet)
                    <div class="col-12">
                        <div class="{{ $cardClasses[($index + 1) % count($cardClasses)] }}" onclick="window.location.href='{{ route('dompet.show', $dompet->id) }}'">
                            <div class="wallet-balance">Rp {{ number_format($dompet->saldo ?? 0, 0, ',', '.') }}</div>
                            <div class="wallet-name">{{ $dompet->nama ?? 'Wallet Name' }}</div>
                            <div class="mt-2">
                                <form id="deleteForm-{{ $dompet->id }}" method="POST" action="{{ route('dompet.destroy', ['dompet' => $dompet->id]) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2" onclick="event.stopPropagation(); confirmDelete({{ $dompet->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="card p-4 text-center text-muted">
                            No wallets found. Click "Add Wallet" to create one.
                        </div>
                    </div>
                    @endforelse

                    <div class="col-12">
                        <a href="{{ route('dompet.create') }}" class="btn btn-primary btn-rounded d-block">
                            <i class="fas fa-plus-circle me-2"></i>Add Wallet
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="main-content">
                <div class="row mb-4 g-4">
                    <div class="col-md-6">
                        <div class="stats-card">
                            <div class="stats-number text-primary">{{ $total_dompet ?? 0 }}</div>
                            <div class="stats-label">Total Wallets</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stats-card">
                            <div class="stats-number text-success">Rp {{ number_format($total_saldo ?? 0, 0, ',', '.') }}</div>
                            <div class="stats-label">Total Balance</div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h4 class="card-title mb-0">Recent Transactions</h4>
                                    <a href="{{ route('transaksi.index') }}" class="btn btn-primary btn-sm">
                                        View All Transactions
                                    </a>
                                </div>

                                @if($recentTransactions->isEmpty())
                                <div class="text-center py-4">
                                    <i class="fas fa-exchange-alt display-1 mb-3 text-muted"></i>
                                    <p class="mb-0">No recent transactions</p>
                                </div>
                                @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Dompet</th>
                                                <th>Kategori</th>
                                                <th>Tipe</th>
                                                <th>Nominal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentTransactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                                <td>{{ $transaction->dompet->nama ?? 'N/A' }}</td>
                                                <td>{{ $transaction->kategori->nama ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $transaction->kategori->tipe === 'pemasukan' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($transaction->kategori->tipe) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold text-{{ $transaction->kategori->tipe === 'pemasukan' ? 'success' : 'danger' }}">
                                                        Rp {{ $transaction->nominal ? number_format($transaction->nominal, 0, ',', '.') : 'N/A' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function showTransactions(walletId) {
        const transactionsContainer = document.getElementById('transactions-container');

        if (!transactionsContainer) return;

        transactionsContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

        fetch(`/api/transactions/${walletId === 'all' ? '' : walletId}`)
            .then(response => response.json())
            .then(data => {
                transactionsContainer.innerHTML = data.html;
            })
            .catch(error => {
                console.error('Error:', error);
                transactionsContainer.innerHTML = '<div class="alert alert-danger">Failed to load transactions</div>';
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        showTransactions('all');
    });
</script>
@extends('layouts.default')

@section('style')
<style>
    /* Base */
    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 1rem;
        background-color: #F9FAFB;
        color: #1F2937;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background-color: #FFFFFF;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .transaction-card {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        margin-bottom: 0.75rem;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .transaction-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        background-color: #F3F4F6;
    }

    .transaction-info {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
    }

    .transaction-date {
        font-size: 0.875rem;
        color: #6B7280;
    }

    .transaction-desc {
        font-weight: 600;
        font-size: 1rem;
        color: #111827;
    }

    .transaction-category {
        display: inline-block;
        margin-top: 0.25rem;
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
    }

    .transaction-amount {
        font-weight: 700;
        font-size: 1.125rem;
    }

    .amount-expense {
        color: #DC2626;
    }

    .amount-income {
        color: #16A34A;
    }

    .header-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .uppercase {
        text-transform: capitalize;
    }

    .category-expense {
        background-color: #FEE2E2;
        color: #C62828;
    }

    .category-income {
        background-color: #D1FAE5;
        color: #065F46;
    }

    .transaction-dompet {
        font-size: 0.875rem;
        color: #6B7280;
        background-color: #F9FAFB;
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
                
                <h3 class="header-title">Transaksi Tabungan ({{ $transaksis->count() }})</h3>
                @forelse ($transaksis as $transaksi)
                <div class="transaction-card" data-toggle="modal" data-target="#modalTransaksi{{ $transaksi->id }}">
                    <div class="transaction-info">
                        <span class="transaction-date">{{ $transaksi->created_at->format('M d, Y') }}</span>
                        <span class="transaction-desc">{{ $transaksi->dompet->nama }}, {{ $transaksi->keterangan }}</span>
                        <span class="transaction-category uppercase {{ $transaksi->tipe === 'deposit' ? 'category-income' : 'category-expense' }}"> {{ $transaksi->tipe }}</span>
                    </div>
                    <div class="transaction-amount {{ $transaksi->tipe === 'deposit' ? 'amount-income' : 'amount-expense' }}">
                        {{ $transaksi->tipe === 'deposit' ? '+' : '-' }} Rp {{ number_format($transaksi->nominal, 2, ',', '.') }}
                    </div>
                </div>
                @empty
                <div class="card-body">
                    <p>Tidak ada transaksi tabungan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
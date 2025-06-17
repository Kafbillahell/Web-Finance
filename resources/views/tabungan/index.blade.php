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

    .pagination {
        justify-content: center;
        margin-top: 2rem;
    }

    .page-link {
        color: #6c757d;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    .page-link:hover {
        color: #0d6efd;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
        border-radius: 0.25rem;
    }

    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        background-color: #fff;
        border-color: #dee2e6;
        border-radius: 0.25rem;
    }
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
            <div class="savings-card">
                <div class="d-flex align-items-center mb-3">
                    <i class="i_tbng fas fa-piggy-bank text-success"></i>
                    <h4 class="mb-0 fw-bold">{{ $tabungan->nama ?? 'Unnamed Goal' }}</h4>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between" data-id="{{ $tabungan->id }}">
                        <small class="text-muted">Saldo</small>
                        <small class="fw-semibold" id="saldo-{{ $tabungan->id }}">Rp {{ number_format($tabungan->saldo, 2, ',', '.') }}</small>
                    </div>
                    <div class="d-flex justify-content-between" data-id="{{ $tabungan->id }}">
                        <small class="text-muted">Target</small>
                        <small class="fw-semibold">Rp {{ number_format($tabungan->target, 2, ',', '.') }}</small>
                    </div>
                    @php
                    $progress = $tabungan->target > 0 ? min(100, ($tabungan->saldo / $tabungan->target) * 100) : 0;
                    @endphp
                    <div class="progress mt-2">
                        <div class="progress-bar bg-success" role="progressbar"
                            style="width: {{ $progress }}%"
                            aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                            <span class="sr-only">{{ $progress }}% Complete</span>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control form-control-sm add-saldo-input" placeholder="Tambah saldo" required min="1">
                            <button type="button" class="btn btn-success btn-sm add-saldo-btn" data-id="{{ $tabungan->id }}">Tambah</button>
                        </div>
                    </div>
                    <!-- <form action="{{ route('tabungan.addSaldo', $tabungan->id) }}" method="POST" class="mt-2">
                        @csrf
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control form-control-sm" placeholder="Tambah saldo" required min="1">
                            <button type="submit" class="btn btn-success btn-sm">Tambah</button>
                        </div>
                    </form> -->
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

    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
            <ul class="pagination mb-0">
                @if ($tabungans->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i>
                    </span>
                </li>
                @else
                <li class="page-item">
                    <a class="page-link" href="{{ $tabungans->previousPageUrl() }}">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
                @endif

                @if($tabungans->currentPage() > 3)
                <li class="page-item">
                    <a class="page-link" href="{{ $tabungans->url(1) }}">1</a>
                </li>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                @endif

                @for ($i = $tabungans->currentPage() - 2; $i <= $tabungans->currentPage() + 2; $i++)
                    @if ($i >= 1 && $i <= $tabungans->lastPage())
                        @if ($i == $tabungans->currentPage())
                        <li class="page-item active">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $tabungans->url($i) }}">{{ $i }}</a>
                        </li>
                        @endif
                        @endif
                        @endfor

                        @if($tabungans->currentPage() < $tabungans->lastPage() - 2)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="{{ $tabungans->url($tabungans->lastPage()) }}">{{ $tabungans->lastPage() }}</a>
                            </li>
                            @endif

                            @if ($tabungans->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $tabungans->nextPageUrl() }}">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                            @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            </li>
                            @endif
            </ul>
        </nav>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('.add-saldo-btn').click(function() {
            const button = $(this);
            const id = button.data('id');
            const input = button.closest('.input-group').find('.add-saldo-input');
            const amount = parseFloat(input.val());

            if (isNaN(amount) || amount <= 0) {
                alert('Masukkan nominal yang valid');
                return;
            }

            $.ajax({
                url: '/tabungan/' + id + '/add-saldo',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    amount: amount
                },
                success: function(response) {
                    // Update the saldo display
                    $('#saldo-' + id).text('Rp ' + response.saldo_formatted);

                    // Update the progress bar
                    const progressBar = $('[data-id="' + id + '"]').find('.progress-bar');
                    const newProgress = Math.min(100, (response.saldo / response.target) * 100);
                    progressBar.css('width', newProgress + '%');
                    progressBar.attr('aria-valuenow', newProgress);
                    progressBar.find('.sr-only').text(newProgress + '% Complete');

                    // Update the progress bar's parent div to trigger reflow
                    const progressBarParent = progressBar.parent();
                    progressBarParent.css('opacity', '0.99');
                    setTimeout(() => progressBarParent.css('opacity', '1'), 10);

                    input.val('');
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    try {
                        const error = JSON.parse(xhr.responseText);
                        alert(error.message || 'Gagal menambahkan saldo');
                    } catch (e) {
                        alert('Gagal menambahkan saldo. Error: ' + xhr.status + ' ' + xhr.statusText);
                    }
                }
            });
        });
    });
</script>
@endsection
@extends('layouts.default')
@section('style')
<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .savings-card {
        border: none;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.03);
        background-color: #ffffff;
        padding: 1rem;
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
        <div class="col-8 align-self-center">
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
        <div class="col-4 align-self-end">
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
                    <div>
                        <div class="mt-3 d-flex justify-content-between">
                            <div>
                                <button class="btn btn-sm btn-success me-2" data-toggle="modal" data-target="#modalSaldo"
                                    data-id="{{ $tabungan->id }}" data-nama="{{ $tabungan->nama }}" data-action="add">
                                    Tambah Saldo
                                </button>
                                <button class="btn btn-sm btn-danger me-2" data-toggle="modal" data-target="#modalSaldo"
                                    data-id="{{ $tabungan->id }}" data-nama="{{ $tabungan->nama }}" data-action="withdraw">
                                    Tarik Saldo
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('tabungan.edit', $tabungan->id) }}" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form id="deleteForm-{{ $tabungan->id }}" action="{{ route('tabungan.destroy', $tabungan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="button" onclick="confirmDelete({{ $tabungan->id }})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between small text-muted mb-3">
                    <div>Dibuat pada: {{ $tabungan->created_at->format('d M Y') }}</div>
                    <div>Created by: {{ $tabungan->user->name ?? 'Unknown' }}</div>
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

<!-- Modal Tambah Saldo -->
<div class="modal fade" id="modalSaldo" tabindex="-1" aria-labelledby="modalSaldoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formSaldo" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSaldoTitle">Judul</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tabungan_id" id="tabunganId">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah Saldo</label>
                        <input type="number" class="form-control" name="amount" id="amount" required min="1">
                    </div>
                    <div class="mb-3">
                        <label for="dompet" class="form-label" id="labelDompet">Dari/Ke Dompet</label>
                        <select class="form-select" name="dompet_id" id="dompet" required>
                            @foreach($dompets as $dompet)
                            <option value="{{ $dompet->id }}">{{ $dompet->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submitButton" class="btn">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        // Saat modal dibuka (baik tambah maupun tarik)
        $('#modalSaldo').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const nama = button.data('nama');
            const action = button.data('action'); // "add" atau "withdraw"

            $('#tabunganId').val(id);

            if (action === 'add') {
                $('#modalSaldoTitle').text('Tambah Saldo ke ' + nama);
                $('#formSaldo').attr('action', '/tabungan/' + id + '/add-saldo');
                $('#submitButton').text('Tambah').removeClass('btn-danger').addClass('btn-primary');
                $('#labelDompet').text('Dari Dompet');
            } else {
                $('#modalSaldoTitle').text('Tarik Saldo dari ' + nama);
                $('#formSaldo').attr('action', '/tabungan/' + id + '/withdraw-saldo');
                $('#submitButton').text('Tarik').removeClass('btn-primary').addClass('btn-danger');
                $('#labelDompet').text('Ke Dompet');
            }
        });

        // Submit form tambah/tarik saldo
        $('#formSaldo').submit(function(e) {
            e.preventDefault();
            const url = $(this).attr('action');

            $.post(url, $(this).serialize(), function(response) {
                location.reload();
            }).fail(function(xhr) {
                alert('Gagal memproses saldo');
            });
        });

        // Tombol kecil langsung tambah/tarik saldo
        $('.add-saldo-btn').click(function() {
            const button = $(this);
            const id = button.data('id');
            const input = button.closest('.input-group').find('.add-saldo-input');
            const amount = parseFloat(input.val());
            const dompetId = $('#dompet').val();

            if (isNaN(amount) || amount <= 0) {
                alert('Masukkan nominal yang valid');
                return;
            }

            const isWithdraw = button.hasClass('btn-danger');
            const url = isWithdraw ? '/tabungan/' + id + '/withdraw-saldo' : '/tabungan/' + id + '/add-saldo';
            const action = isWithdraw ? 'menarik' : 'menambahkan';

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    amount: amount,
                    dompet_id: dompetId

                },
                success: function(response) {
                    if (response.error) {
                        alert(response.message);
                        return;
                    }

                    // Update saldo dan progress
                    $('#saldo-' + id).text('Rp ' + response.saldo_formatted);
                    const progressBar = $('[data-id="' + id + '"]').find('.progress-bar');
                    const newProgress = Math.min(100, (response.saldo / response.target) * 100);
                    progressBar.css('width', newProgress + '%');
                    progressBar.attr('aria-valuenow', newProgress);
                    progressBar.find('.sr-only').text(newProgress.toFixed(0) + '% Complete');

                    // Reflow efek
                    const progressBarParent = progressBar.parent();
                    progressBarParent.css('opacity', '0.99');
                    setTimeout(() => progressBarParent.css('opacity', '1'), 10);

                    input.val('');
                    alert('Berhasil ' + action + ' saldo!');
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    try {
                        const error = JSON.parse(xhr.responseText);
                        alert(error.message || 'Gagal ' + action + ' saldo');
                    } catch (e) {
                        alert('Gagal ' + action + ' saldo. Error: ' + xhr.status + ' ' + xhr.statusText);
                    }
                }
            });
        });
    });
</script>
@endsection
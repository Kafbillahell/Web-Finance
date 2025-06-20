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
                        <input class="form-control me-2" type="search" name="search" id="liveSearchInput"
                            placeholder="Search tabungan...">
                        <button class="btn btn-outline-success" type="submit">
                            <i class="fas fa-search"></i> </button>
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
                                <a class="page-link"
                                    href="{{ $tabungans->url($tabungans->lastPage()) }}">{{ $tabungans->lastPage() }}</a>
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
                    {{-- CHANGE 'btn-close' to 'close' for Bootstrap 4 --}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span> {{-- Add this for the 'x' icon in Bootstrap 4 --}}
                    </button>
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
        let searchTimeout;
        const searchInput = $('#liveSearchInput');
        const tabunganContainer = $('.container-fluid > .row');
        const paginationContainer = $('.d-flex.justify-content-center.mt-4');
        let modalSaldo = $('#modalSaldo'); // Gunakan 'let' agar bisa di-reassign
        const formSaldo = $('#formSaldo');

        // --- Diagnostik Penting untuk Debugging Modal ---
        modalSaldo.on('show.bs.modal', function() {
            console.log('MODAL EVENT: show.bs.modal - Modal is about to be shown.');
            if ($('.modal-backdrop').length) {
                console.warn('MODAL WARNING: Modal backdrop detected before show.bs.modal. Forcing removal.');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            }
        });
        modalSaldo.on('shown.bs.modal', function() {
            console.log('MODAL EVENT: shown.bs.modal - Modal is fully shown.');
            console.log('  Body classes:', $('body').attr('class'));
            console.log('  Modal backdrop count:', $('.modal-backdrop').length);
        });
        modalSaldo.on('hide.bs.modal', function() {
            console.log('MODAL EVENT: hide.bs.modal - Modal is about to be hidden.');
        });
        modalSaldo.on('hidden.bs.modal', function() {
            console.log('MODAL EVENT: hidden.bs.modal - Modal is fully hidden. Starting cleanup process...');

            setTimeout(function() {
                if ($('.modal-backdrop').length) {
                    console.warn('MODAL WARNING: Modal backdrop still present. Force removing.');
                    $('.modal-backdrop').remove();
                }
                if ($('body').hasClass('modal-open')) {
                    console.warn('MODAL WARNING: body still has modal-open class. Force removing.');
                    $('body').removeClass('modal-open');
                }
                if ($('body').css('padding-right') !== '') {
                    console.warn('MODAL WARNING: body still has padding-right. Force resetting.');
                    $('body').css('padding-right', '');
                }

                if (modalSaldo.data('bs.modal')) {
                    modalSaldo.modal('dispose');
                    console.log('MODAL INFO: Modal disposed to fully reset its state.');
                }

                // Cek dan tampilkan alert jika ada pesan yang tersimpan
                const alertMessage = modalSaldo.data('alert-message');
                if (alertMessage) {
                    alert(alertMessage);
                    modalSaldo.removeData('alert-message'); // Hapus pesan setelah ditampilkan
                }

                // Reset form di sini, setelah alert dan cleanup
                formSaldo[0].reset();
                console.log('MODAL INFO: Form reset after modal hidden.');

                // Re-initialize modal
                modalSaldo = $('#modalSaldo');
                modalSaldo.modal({
                    backdrop: true,
                    keyboard: true,
                    focus: true,
                    show: false
                });
                console.log('MODAL INFO: Modal re-initialized for next use.');

            }, 150);
        });
        // --- Akhir Diagnostik ---

        // Function to format currency
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(number);
        }

        // Function to render a single tabungan card
        function renderTabunganCard(tabungan) {
            const progress = tabungan.target > 0 ? Math.min(100, (tabungan.saldo / tabungan.target) * 100) : 0;
            const formattedSaldo = formatRupiah(tabungan.saldo);
            const formattedTarget = formatRupiah(tabungan.target);
            const createdAt = new Date(tabungan.created_at).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            const userName = tabungan.user ? tabungan.user.name : 'Unknown';

            return `
                    <div class="col-12 mb-4">
                        <div class="savings-card">
                            <div class="d-flex align-items-center mb-3">
                                <i class="i_tbng fas fa-piggy-bank text-success"></i>
                                <h4 class="mb-0 fw-bold">${tabungan.nama ?? 'Unnamed Goal'}</h4>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between" data-id="${tabungan.id}">
                                    <small class="text-muted">Saldo</small>
                                    <small class="fw-semibold" id="saldo-${tabungan.id}">${formattedSaldo}</small>
                                </div>
                                <div class="d-flex justify-content-between" data-id="${tabungan.id}">
                                    <small class="text-muted">Target</small>
                                    <small class="fw-semibold">${formattedTarget}</small>
                                </div>
                                <div class="progress mt-2">
                                    <div class="progress-bar bg-success" role="progressbar"
                                        style="width: ${progress}%"
                                        aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100">
                                        <span class="sr-only">${progress.toFixed(0)}% Complete</span>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalSaldo"
                                        data-id="${tabungan.id}" data-nama="${tabungan.nama}" data-action="add">
                                        Tambah Saldo
                                    </button>
                                    <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalSaldo"
                                        data-id="${tabungan.id}" data-nama="${tabungan.nama}" data-action="withdraw">
                                        Tarik Saldo
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between small text-muted mb-3">
                                <div>Dibuat pada: ${createdAt}</div>
                                <div>Created by: ${userName}</div>
                            </div>

                            <div class="text-end">
                                <a href="/tabungan/${tabungan.id}/edit" class="btn btn-sm btn-outline-primary me-2">
                                    <i class="fas fa-pen"></i> Edit
                                </a>
                                <form id="deleteForm-${tabungan.id}" action="/tabungan/${tabungan.id}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="button" onclick="confirmDelete(${tabungan.id})">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                `;
        }

        // --- Live Search functionality ---
        searchInput.on('input', function() {
            clearTimeout(searchTimeout);
            const searchTerm = $(this).val();

            if (searchTerm.length >= 1 || searchTerm.length === 0) {
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: "{{ route('tabungan.index') }}",
                        method: 'GET',
                        data: {
                            search: searchTerm,
                            ajax: true
                        },
                        success: function(response) {
                            tabunganContainer.empty();
                            if (response.tabungans.length > 0) {
                                response.tabungans.forEach(function(tabungan) {
                                    tabunganContainer.append(renderTabunganCard(tabungan));
                                });
                            } else {
                                tabunganContainer.append('<div class="col-12"><p class="text-center">Tidak ada tabungan yang ditemukan.</p></div>');
                            }
                            paginationContainer.html(response.pagination);
                            attachModalEventListeners();
                        },
                        error: function(xhr) {
                            console.error('Error during live search:', xhr.responseText);
                            alert('Failed to fetch search results.');
                        }
                    });
                }, 0);
            }
        });

        // --- Pagination links for AJAX ---
        $(document).on('click', '.pagination a.page-link', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            const searchTerm = searchInput.val();

            $.ajax({
                url: url,
                method: 'GET',
                data: {
                    search: searchTerm,
                    ajax: true
                },
                success: function(response) {
                    tabunganContainer.empty();
                    response.tabungans.forEach(function(tabungan) {
                        tabunganContainer.append(renderTabunganCard(tabungan));
                    });
                    paginationContainer.html(response.pagination);
                    attachModalEventListeners();
                },
                error: function(xhr) {
                    console.error('Error during pagination:', xhr.responseText);
                    alert('Failed to load pagination results.');
                }
            });
        });

        // --- Function to attach event listeners for the modal buttons ---
        function attachModalEventListeners() {
            // IMPORTANT: Use .off('click') to prevent duplicate handlers
            $(document).off('click', '[data-toggle="modal"][data-target="#modalSaldo"]').on('click', '[data-toggle="modal"][data-target="#modalSaldo"]', function(event) {
                const button = $(event.currentTarget);
                const id = button.data('id');
                const nama = button.data('nama');
                const action = button.data('action');

                $('#tabunganId').val(id);
                $('#amount').val(''); // Clear any previous input value

                if (action === 'add') {
                    $('#modalSaldoTitle').text('Tambah Saldo ke ' + nama);
                    formSaldo.attr('action', '/tabungan/' + id + '/add-saldo');
                    $('#submitButton').text('Tambah').removeClass('btn-danger').addClass('btn-primary');
                    $('#labelDompet').text('Dari Dompet');
                } else {
                    $('#modalSaldoTitle').text('Tarik Saldo dari ' + nama);
                    formSaldo.attr('action', '/tabungan/' + id + '/withdraw-saldo');
                    $('#submitButton').text('Tarik').removeClass('btn-primary').addClass('btn-danger');
                    $('#labelDompet').text('Ke Dompet');
                }
                modalSaldo.modal('show');
            });

            // **TAMBAHAN PENTING:** Event listener untuk tombol "X" (close) di modal
            // Menggunakan delegated event untuk memastikan event tetap berfungsi pada elemen yang dinamis
            $(document).off('click', '#modalSaldo .close').on('click', '#modalSaldo .close', function() {
                console.log('Clicked modal close button (X). Hiding modal.');
                modalSaldo.modal('hide');
            });
        }

        attachModalEventListeners(); // Initial call for buttons present on page load

        // --- Submit form tambah/tarik saldo ---
        formSaldo.submit(function(e) {
            e.preventDefault();
            const url = $(this).attr('action');
            const formData = $(this).serialize();

            $.post(url, formData, function(response) {
                try {
                    if (response.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Terjadi kesalahan saat memproses saldo.',
                            confirmButtonColor: '#d33'
                        });
                        console.log('AJAX SUCCESS (with error): Message:', response.message);
                    } else {
                        const tabunganId = $('#tabunganId').val();
                        const cardElement = $('[data-id="' + tabunganId + '"]').closest('.savings-card');
                        if (cardElement.length) {
                            $('#saldo-' + tabunganId).text(response.saldo_formatted);
                            const progressBar = cardElement.find('.progress-bar');
                            const newProgress = Math.min(100, (response.saldo / response.target) * 100);
                            progressBar.css('width', newProgress + '%');
                            progressBar.attr('aria-valuenow', newProgress);
                            progressBar.find('.sr-only').text(newProgress.toFixed(0) + '% Complete');
                            console.log('AJAX SUCCESS: UI updated for tabungan ID:', tabunganId);
                        }

                        console.log('AJAX SUCCESS: Attempting to hide modal.');
                        modalSaldo.modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message || 'Saldo berhasil diperbarui.',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                } catch (error) {
                    console.error('AJAX SUCCESS CALLBACK ERROR:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: 'Terjadi kesalahan saat memproses respons.',
                        confirmButtonColor: '#d33'
                    });
                }
            }).fail(function(xhr) {
                console.error('AJAX FAILED:', xhr.responseText);
                try {
                    const error = JSON.parse(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: error.message || 'Gagal memproses saldo.',
                        confirmButtonColor: '#d33'
                    });
                } catch (e) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan!',
                        text: 'Gagal memproses saldo. Error: ' + xhr.status + ' ' + xhr.statusText,
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });

        // --- Confirm delete function ---
        // window.confirmDelete = function(id) {
        //     if (confirm('Apakah Anda yakin ingin menghapus tabungan ini?')) {
        //         $(`#deleteForm-${id}`).is('form') ? $(`#deleteForm-${id}`).submit() : console.error('Delete form not found or is not a form element.');
        //     }
        // };
    });
</script>
@endsection
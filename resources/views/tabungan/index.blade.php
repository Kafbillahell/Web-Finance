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
                                <small class="fw-semibold" id="saldo-{{ $tabungan->id }}">Rp
                                    {{ number_format($tabungan->saldo, 2, ',', '.') }}</small>
                            </div>
                            <div class="d-flex justify-content-between" data-id="{{ $tabungan->id }}">
                                <small class="text-muted">Target</small>
                                <small class="fw-semibold">Rp {{ number_format($tabungan->target, 2, ',', '.') }}</small>
                            </div>
                            @php
                                $progress = $tabungan->target > 0 ? min(100, ($tabungan->saldo / $tabungan->target) * 100) : 0;
                            @endphp
                            <div class="progress mt-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%"
                                    aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                    <span class="sr-only">{{ $progress }}% Complete</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalSaldo"
                                    data-id="{{ $tabungan->id }}" data-nama="{{ $tabungan->nama }}" data-action="add">
                                    Tambah Saldo
                                </button>

                                <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#modalSaldo"
                                    data-id="{{ $tabungan->id }}" data-nama="{{ $tabungan->nama }}" data-action="withdraw">
                                    Tarik Saldo
                                </button>
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
                            <form id="deleteForm-{{ $tabungan->id }}" action="{{ route('tabungan.destroy', $tabungan->id) }}"
                                method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="button"
                                    onclick="confirmDelete({{ $tabungan->id }})">
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
        $(document).ready(function () {
            let searchTimeout;
            const searchInput = $('#liveSearchInput');
            const tabunganContainer = $('.container-fluid > .row');
            const paginationContainer = $('.d-flex.justify-content-center.mt-4');
            const modalSaldo = $('#modalSaldo'); // Ambil referensi modal
            const formSaldo = $('#formSaldo');   // Ambil referensi form modal

            // Tambahkan elemen untuk loading spinner
            // Letakkan spinner di dalam form pencarian agar sejajar dengan input
            const searchForm = searchInput.closest('form');
            const loadingSpinner = $('<div class="spinner-border spinner-border-sm text-primary ms-2" role="status" style="display: none;"><span class="visually-hidden">Loading...</span></div>');
            searchForm.append(loadingSpinner);

            // --- FUNGSI UNTUK MENGATASI MODAL BACKDROP TETAP ABU-ABU (Bootstrap 4) ---
            modalSaldo.on('hidden.bs.modal', function () {
                // Hapus backdrop secara manual jika masih ada
                $('.modal-backdrop').remove();
                // Juga, pastikan class 'modal-open' hilang dari body
                $('body').removeClass('modal-open');
            });

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
                const createdAt = new Date(tabungan.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
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

            // --- FUNGSI UTAMA UNTUK MELAKUKAN PENCARIAN AJAX ---
            function performSearch(searchTerm, page = 1) {
                loadingSpinner.show(); // Tampilkan spinner
                $.ajax({
                    url: "{{ route('tabungan.index') }}",
                    method: 'GET',
                    data: { search: searchTerm, ajax: true, page: page },
                    success: function (response) {
                        tabunganContainer.empty();
                        if (response.tabungans.length > 0) {
                            response.tabungans.forEach(function (tabungan) {
                                tabunganContainer.append(renderTabunganCard(tabungan));
                            });
                        } else {
                            tabunganContainer.append('<div class="col-12"><p class="text-center text-muted">Tidak ada tabungan yang ditemukan.</p></div>');
                        }
                        paginationContainer.html(response.pagination);
                        attachModalEventListeners(); // Re-attach listeners for new buttons
                    },
                    error: function (xhr) {
                        console.error('Error during live search:', xhr.responseText);
                        alert('Gagal mengambil hasil pencarian.');
                    },
                    complete: function () {
                        loadingSpinner.hide(); // Sembunyikan spinner setelah request selesai
                    }
                });
            }

            // Live Search functionality
            searchInput.on('input', function () {
                clearTimeout(searchTimeout); // Clear previous timeout
                const searchTerm = $(this).val();

                // Panggil pencarian jika minimal 1 karakter atau jika input kosong (untuk reset)
                if (searchTerm.length >= 1 || searchTerm.length === 0) {
                    searchTimeout = setTimeout(function () {
                        performSearch(searchTerm);
                    }, 300); // Sesuaikan delay ini jika perlu (misal: 200, 300, 500)
                }
            });

            // Pagination links for AJAX
            $(document).on('click', '.pagination a.page-link', function (e) {
                e.preventDefault();
                const url = new URL($(this).attr('href'));
                const page = url.searchParams.get('page'); // Dapatkan nomor halaman dari URL
                const searchTerm = searchInput.val(); // Ambil searchTerm terbaru dari input

                performSearch(searchTerm, page); // Panggil fungsi pencarian utama dengan nomor halaman
            });

            // --- FUNGSI UNTUK MELAMPIRKAN EVENT LISTENER KE TOMBOL MODAL ---
            function attachModalEventListeners() {
                // Menggunakan event delegation karena card dirender ulang
                $(document).off('click', '[data-toggle="modal"][data-target="#modalSaldo"]') // Hapus listener lama jika ada
                       .on('click', '[data-toggle="modal"][data-target="#modalSaldo"]', function (event) {
                    const button = $(event.currentTarget);
                    const id = button.data('id');
                    const nama = button.data('nama');
                    const action = button.data('action');

                    $('#tabunganId').val(id);
                    $('#amount').val(''); // Kosongkan input amount setiap kali modal dibuka

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
                    // Tampilkan modal secara manual
                    modalSaldo.modal('show');
                });
            }

            // Panggil saat halaman pertama kali dimuat untuk tombol yang sudah ada
            attachModalEventListeners();

            // Submit form tambah/tarik saldo
            formSaldo.submit(function (e) {
                e.preventDefault();
                const url = $(this).attr('action');
                const formData = $(this).serialize();

                $.post(url, formData, function (response) {
                    if (response.error) {
                        alert(response.message);
                    } else {
                        // Update tampilan saldo pada kartu yang bersangkutan
                        const tabunganId = $('#tabunganId').val();
                        const tabunganCard = $('[data-id="' + tabunganId + '"]').closest('.savings-card');

                        // Update Saldo
                        tabunganCard.find('#saldo-' + tabunganId).text(response.saldo_formatted);

                        // Update Progress Bar
                        const progressBar = tabunganCard.find('.progress-bar');
                        const newProgress = Math.min(100, (response.saldo / response.target) * 100);
                        progressBar.css('width', newProgress + '%');
                        progressBar.attr('aria-valuenow', newProgress);
                        progressBar.find('.sr-only').text(newProgress.toFixed(0) + '% Complete');

                        // Tutup modal
                        modalSaldo.modal('hide'); // Menggunakan referensi modal yang sudah disimpan
                        alert(response.message); // Tampilkan pesan sukses
                    }
                }).fail(function (xhr) {
                    console.error('Error processing saldo:', xhr.responseText);
                    try {
                        const error = JSON.parse(xhr.responseText);
                        alert(error.message || 'Gagal memproses saldo');
                    } catch (e) {
                        alert('Gagal memproses saldo. Error: ' + xhr.status + ' ' + xhr.statusText);
                    }
                });
            });

            // Confirm delete function (make sure it's accessible globally or correctly scoped)
            window.confirmDelete = function (id) {
                if (confirm('Apakah Anda yakin ingin menghapus tabungan ini?')) {
                    $(`#deleteForm-${id}`).submit();
                }
            };
        });
    </script>
@endsection
@extends('layouts.default')
@section('style')
<link href="{{asset('assets')}}/extra-libs/c3/c3.min.css" rel="stylesheet">
<link href="{{asset('assets')}}/libs/chartist/dist/chartist.min.css" rel="stylesheet">
<link href="{{asset('assets')}}/extra-libs/jvector/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
<link href="{{asset('assets')}}/libs/morris.js/morris.css" rel="stylesheet">
<!-- Custom CSS -->
<link href="{{asset('assets')}}/dist/css/style.min.css" rel="stylesheet">

<style>
    body {
        font-family: 'Inter', sans-serif;
    }
</style>
@endsection

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">My Balance</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="#">Workspace</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dompetku</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="card-group">
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                class="set-doller">$</sup>Rp{{ number_format($totalSaldo, 0, ',', '.') }}</h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Dompet-KU</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                class="set-doller">$</sup>Rp{{ number_format($totalTabungan, 0, ',', '.') }}</h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Total Tabungan</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                class="set-doller">$</sup>Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pemasukan</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-right">
            <div class="card-body">
                <div class="d-flex d-lg-flex d-md-block align-items-center">
                    <div>
                        <h2 class="text-dark mb-1 w-100 text-truncate font-weight-medium"><sup
                                class="set-doller">$</sup>Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</h2>
                        <h6 class="text-muted font-weight-normal mb-0 w-100 text-truncate">Pengeluaran</h6>
                    </div>
                    <div class="ml-auto mt-md-3 mt-lg-0">
                        <span class="opacity-7 text-muted"><i data-feather="dollar-sign"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Saldo --}}
    <div class="card p-4 mb-4 d-flex flex-row justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <div class="bg-light-success text-success rounded-circle p-3 me-3">
                <i class="fas fa-dollar-sign fa-2x"></i>
            </div>
            <div>
                <div class="text-muted small">Dompet-KU</div>
                <h5 class="mb-0">Rp{{ number_format($totalSaldo, 0, ',', '.') }}</h5>
            </div>
        </div>
        @if($dompet->isNotEmpty())
        <button class="btn btn-outline-success d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#withdrawModal">
            <i class="fas fa-paper-plane me-2"></i> Withdraw
        </button>
        @endif
    </div>

  {{-- Modal Deposit --}}
<div class="modal fade" id="depositModal" tabindex="-1" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="depositForm" method="POST" action="{{ route('dashboard.store') }}" class="modal-content">
            @csrf
            <input type="hidden" name="tipe" value="deposit">
            <div class="modal-header">
                <h5 class="modal-title" id="depositModalLabel">Deposit Saldo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="depositAmount" class="form-label">Jumlah Deposit</label>
                    <input type="number" name="amount" class="form-control" id="depositAmount" min="1" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="dompet_id_deposit" class="form-label">Pilih Dompet</label>
                    <select id="dompet_id_deposit" name="dompet_id" class="form-control" required>
                        @foreach($dompet as $item)
                            <option value="{{ $item->id }}" data-saldo="{{ $item->saldo }}">{{ $item->nama }} (Rp{{ number_format($item->saldo, 0, ',', '.') }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Deposit</button>
            </div>
        </form>
    </div>
</div>

        {{-- Modal Withdraw --}}
        <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="withdrawForm" method="POST" action="{{ route('dashboard.store') }}" class="modal-content">
                    @csrf
                    <input type="hidden" name="tipe" value="withdraw">
                    <div class="modal-header">
                        <h5 class="modal-title" id="withdrawModalLabel">Withdraw Saldo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="withdrawAmount" class="form-label">Jumlah Withdraw</label>
                            <input type="number" name="amount" class="form-control" id="withdrawAmount" min="1" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="dompet_id_withdraw" class="form-label">Pilih Dompet</label>
                            <select id="dompet_id_withdraw" name="dompet_id" class="form-control" required>
                                @foreach($dompet as $item)
                                <option value="{{ $item->id }}" data-saldo="{{ $item->saldo }}">{{ $item->nama }} (Rp{{ number_format($item->saldo, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-success">Withdraw</button>
                    </div>
                </form>
            </div>
        </div>

        @if($dompet->isEmpty())
        <div class="card text-center py-5">
            <div class="card-body">
                <i class="fas fa-clipboard fa-4x text-muted mb-3"></i>
                <h5 class="card-title">You do not have any wallets yet.</h5>
                <a href="{{ route('dompet.create') }}" class="btn btn-primary mt-3">Buat Dompet Baru</a>
            </div>
        </div>
        @endif

    </div>
    @endsection

    @section('scripts')
    <script src="{{asset('assets')}}/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{asset('assets')}}/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{asset('assets')}}/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- apps -->
    <!-- apps -->
    <script src="{{asset('assets')}}/js/app-style-switcher.js"></script>
    <script src="{{asset('assets')}}/js/feather.min.js"></script>
    <script src="{{asset('assets')}}/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="{{asset('assets')}}/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('assets')}}/js/custom.min.js"></script>
    <!--This page JavaScript -->
    <script src="{{asset('assets')}}/extra-libs/c3/d3.min.js"></script>
    <script src="{{asset('assets')}}/extra-libs/c3/c3.min.js"></script>
    <script src="{{asset('assets')}}/libs/chartist/dist/chartist.min.js"></script>
    <script src="{{asset('assets')}}/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="{{asset('assets')}}/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{asset('assets')}}/extra-libs/jvector/jquery-jvectormap-world-mill-en.js"></script>
    <script src="{{asset('assets')}}/js/pages/dashboards/dashboard1.min.js"></script>
    <script src="{{asset('assets')}}/libs/raphael/raphael.min.js"></script>
    <script src="{{asset('assets')}}/libs/morris.js/morris.min.js"></script>
    <script src="{{asset('assets')}}/dist/js/pages/morris/morris-data.js"></script>

    <script>
        // Deposit form validation
        document.getElementById('depositForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('depositAmount').value);
            if (isNaN(amount) || amount < 1) {
                e.preventDefault();
                alert('Jumlah deposit harus lebih dari atau sama dengan Rp1.');
                return;
            }

            const dompetSelect = document.getElementById('dompet_id_deposit');
            if (!dompetSelect.value) {
                e.preventDefault();
                alert('Silakan pilih dompet terlebih dahulu.');
                return;
            }
        });

        // Withdraw form validation
        document.getElementById('withdrawForm').addEventListener('submit', function(e) {
            const amount = parseFloat(document.getElementById('withdrawAmount').value);
            const selectedOption = document.getElementById('dompet_id_withdraw').selectedOptions[0];
            const saldo = parseFloat(selectedOption.getAttribute('data-saldo'));

            if (isNaN(amount) || amount < 1) {
                e.preventDefault();
                alert('Jumlah withdraw harus lebih dari atau sama dengan Rp1.');
                return;
            }

            if (amount > saldo) {
                e.preventDefault();
                alert('Saldo tidak mencukupi untuk withdraw.');
                return;
            }

            const dompetSelect = document.getElementById('dompet_id_withdraw');
            if (!dompetSelect.value) {
                e.preventDefault();
                alert('Silakan pilih dompet terlebih dahulu.');
                return;
            }
        });

        // Initialize Bootstrap modals
        document.addEventListener('DOMContentLoaded', function() {
            const depositModal = new bootstrap.Modal(document.getElementById('depositModal'));
            const withdrawModal = new bootstrap.Modal(document.getElementById('withdrawModal'));

            // Handle deposit form submission
            const depositForm = document.getElementById('depositForm');
            const dompetSelectDeposit = document.getElementById('dompet_id_deposit');

            if (depositForm && dompetSelectDeposit) {
                depositForm.addEventListener('submit', function(e) {
                    // Get the form data
                    const formData = new FormData(depositForm);

                    // Set the action URL with the selected dompet ID
                    depositForm.action = "{{ route('dompet.deposit', '__id__') }}".replace('__id__', dompetSelectDeposit.value);

                    // Remove the dompet_id field since it's passed in the URL
                    formData.delete('dompet_id');
                    formData.delete('tipe');
                });
            }

            // Handle withdraw form submission
            const withdrawForm = document.getElementById('withdrawForm');
            const dompetSelectWithdraw = document.getElementById('dompet_id_withdraw');

            if (withdrawForm && dompetSelectWithdraw) {
                withdrawForm.addEventListener('submit', function(e) {
                    // Get the form data
                    const formData = new FormData(withdrawForm);

                    // Set the action URL with the selected dompet ID
                    withdrawForm.action = "{{ route('dompet.withdraw', '__id__') }}".replace('__id__', dompetSelectWithdraw.value);

                    // Remove the dompet_id field since it's passed in the URL
                    formData.delete('dompet_id');
                    formData.delete('tipe');
                });
            }
        });
    </script>
    @endsection
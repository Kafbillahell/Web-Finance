@extends('layouts.default')

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

    {{-- Tombol Deposit --}}
    @if($dompet->isNotEmpty())
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#depositModal">
                <i class="fas fa-wallet me-2"></i> Deposit
            </button>
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

{{-- VALIDASI JAVASCRIPT --}}
<script>
document.getElementById('depositForm').addEventListener('submit', function(e) {
    const amount = parseFloat(document.getElementById('depositAmount').value);
    if (isNaN(amount) || amount < 1) {
        e.preventDefault();
        alert('Jumlah deposit harus lebih dari atau sama dengan Rp1.');
    }
});

document.getElementById('withdrawForm').addEventListener('submit', function(e) {
    const amount = parseFloat(document.getElementById('withdrawAmount').value);
    const selectedOption = document.getElementById('dompet_id_withdraw').selectedOptions[0];
    const saldo = parseFloat(selectedOption.getAttribute('data-saldo'));

    if (isNaN(amount) || amount < 1) {
        e.preventDefault();
        alert('Jumlah withdraw harus lebih dari atau sama dengan Rp1.');
    } else if (amount > saldo) {
        e.preventDefault();
        alert('Saldo tidak mencukupi untuk withdraw.');
    }
});
</script>
    
    {{-- Daftar Dompet --}}             


    {{-- Data Tidak Ada --}}
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const depositForm = document.getElementById('depositForm');
        const dompetSelectDeposit = document.getElementById('dompet_id_deposit');
        const withdrawForm = document.getElementById('withdrawForm');
        const dompetSelectWithdraw = document.getElementById('dompet_id_withdraw');

        depositForm.addEventListener('submit', function (e) {
            depositForm.action = "{{ url('dompet') }}/" + dompetSelectDeposit.value + "/deposit";
        });

        withdrawForm.addEventListener('submit', function (e) {
            withdrawForm.action = "{{ url('dompet') }}/" + dompetSelectWithdraw.value + "/withdraw";
        });
    });
</script>
@endpush

@endsection

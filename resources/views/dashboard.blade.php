@extends('layouts.default')

@section('style')
@if(Auth::user()->role == 'user')
<link href="{{ asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/libs/chartist/dist/chartist.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/libs/morris.js/morris.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/style.min.css') }}" rel="stylesheet">
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
            <h4 class="page-title">My Balance</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="#">Workspace</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dompetku</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<script>
    window.monthlyData = @json($monthlyTotals);
</script>

<div class="container-fluid">
    <div class="card-group">
        @foreach(['Saldo' => $totalSaldo, 'Tabungan' => $totalTabungan, 'Pemasukan' => $totalPemasukan, 'Pengeluaran' => $totalPengeluaran] as $label => $value)
        <div class="card border-right">
            <div class="card-body d-flex align-items-center">
                <div>
                    <h2 class="text-dark font-weight-medium">
                        Rp{{ number_format($value,0,',','.') }}
                    </h2>
                    <h6 class="text-muted">{{ $label }}</h6>
                </div>
                <div class="ml-auto text-muted"><i data-feather="dollar-sign"></i></div>
            </div>
        </div>
        @endforeach
    </div>


    <div class="card p-4 mb-4 d-flex justify-content-between align-items-center shadow-sm rounded-3">
        <div class="d-flex align-items-center">
            <div class="bg-light-success text-success rounded-circle p-3 me-3">
                <i class="fas fa-wallet fa-2x"></i>
            </div>
            <div>
                <small class="text-muted">Saldo Dompet</small>
                <h4 class="mb-0 fw-semibold text-success">Rp{{ number_format($totalSaldo,0,',','.') }}</h4>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success px-4" data-bs-toggle="modal" data-bs-target="#depositModal">
                <i class="fas fa-plus me-2"></i> Deposit
            </button>
            @if($dompet->isNotEmpty())
            <button class="btn btn-outline-success px-4" data-bs-toggle="modal" data-bs-target="#withdrawModal">
                <i class="fas fa-paper-plane me-2"></i> Withdraw
            </button>
            @endif
            <a href="{{ route('dompet.index') }}" class="btn btn-success px-4">
                <i class="fas fa-list me-2"></i> Detail
            </a>
        </div>
    </div>


    <!-- Modals -->
    @foreach(['deposit' => 'Deposit Saldo', 'withdraw' => 'Withdraw Saldo'] as $type => $title)
    <div class="modal fade" id="{{ $type }}Modal" tabindex="-1" aria-labelledby="{{ $type }}ModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form id="{{ $type }}Form" method="POST" action="{{ route('dashboard.store') }}" class="modal-content">
                @csrf
                <input type="hidden" name="tipe" value="{{ $type }}">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="{{ $type }}ModalLabel"><i class="fas fa-{{ $type=='deposit'? 'plus':'paper-plane' }} me-2"></i> {{ $title }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="{{ $type }}Amount" class="form-label">Jumlah {{ ucfirst($type) }}</label>
                        <input type="number" name="amount" id="{{ $type }}Amount" class="form-control" min="1" step="0.01" placeholder="Misal: 50000" required>
                    </div>
                    <div class="mb-3">
                        <label for="dompet_id_{{ $type }}" class="form-label">Pilih Dompet</label>
                        <select id="dompet_id_{{ $type }}" name="dompet_id" class="form-select" required>
                            @foreach($dompet as $item)
                            <option value="{{ $item->id }}">{{ $item->nama }} (Rp{{ number_format($item->saldo,0,',','.') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">{{ ucfirst($type) }}</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach

    <div class="row">
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total Kategori</h4>
                    <div id="kategori-data" data-json='@json($kategoriTransaksi)'></div>
                    <div id="campaign-v2" class="mt-2" style="height:283px; width:100%;"></div>
                    <ul class="list-style-none mb-0">
                        @php
                        $chartColors = ['#5f76e8', '#ff4f70', '#01caf1', '#ffc107', '#4caf50', '#edf2f6'];
                        @endphp
                        @foreach($kategoriTransaksi as $index => $row)
                        <li>
                            <i class="fas fa-circle font-10 mr-2" style="color: {{ $chartColors[$index % count($chartColors)] }}"></i>
                            <span class="text-muted">{{ $row->nama }}</span>
                            <span class="text-dark float-right font-weight-medium">Rp {{ number_format($row->total_nominal, 0, ',', '.') }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Bar Chart</h4>
                    <div id="morris-bar-chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Recent Transactions</h4>
                        <div>
                            <a href="{{ route('transaksi.index') }}" class="btn btn-primary btn-sm me-2">
                                View All Transactions
                            </a>
                            <a href=" {{ route ('export.transaksi', 'recent')  }}" class="btn btn-primary btn-sm">
                                Export Transactions
                            </a>
                        </div>
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
                                        <span class="badge bg-{{ $transaction->kategori->tipe   === 'pemasukan' ? 'success' : 'danger' }}">
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
@endif
@endsection

@section('scripts')
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
<script src="{{asset('assets')}}/js/pages/morris/morris-data.js"></script>
<script src="{{asset('assets')}}/js/pages/dashboard/dashboard.js"></script>
@endsection
@extends('layouts.default')

@section('style')
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
@if(Auth::user()->role == 'admin')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title">Admin Dashboard</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb m-0 p-0">
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card-group">
        @foreach([
        'Total Users' => $userCount,
        'Total Dompet' => $dompetCount,
        'Total Transaksi' => $transaksiCount,
        'Total Tabungan' => $tabunganCount
        ] as $label => $value)
        <div class="card border-right">
            <div class="card-body d-flex align-items-center">
                <div>
                    <h2 class="text-dark font-weight-medium">{{ $value }}</h2>
                    <h6 class="text-muted">{{ $label }}</h6>
                </div>
                <div class="ml-auto text-muted"><i data-feather="bar-chart-2"></i></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transaksi per Bulan</h4>
                    <div id="morris-admin-bar-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Kategori Pengeluaran</h4>
                    <div id="admin-kategori-data" data-json='@json($adminKategoriChart)'></div>
                    <canvas id="adminPieChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Transaksi Terbaru Seluruh Pengguna</h4>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Dompet</th>
                            <th>Kategori</th>
                            <th>Tipe</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentAllTransactions as $trx)
                        <tr>
                            <td>{{ $trx->user->name ?? '-' }}</td>
                            <td>{{ $trx->dompet->nama ?? '-' }}</td>
                            <td>{{ $trx->kategori->nama ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $trx->kategori->tipe == 'pemasukan' ? 'success' : 'danger' }}">
                                    {{ ucfirst($trx->kategori->tipe) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                            <td>{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
@if(Auth::user()->role == 'admin')
<script src="{{ asset('assets/libs/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('assets/libs/morris.js/morris.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthlyData = @json($monthlyTotals);

        // Bar Chart tetap pakai Morris
        new Morris.Bar({
            element: 'morris-admin-bar-chart',
            data: monthlyData,
            xkey: 'bulan',
            ykeys: ['pemasukan', 'pengeluaran'],
            labels: ['Pemasukan', 'Pengeluaran'],
            barColors: ['#4caf50', '#f44336'],
            hideHover: 'auto',
            resize: true
        });

        // Pie Chart dengan Chart.js
        const pieData = JSON.parse(document.getElementById('admin-kategori-data').dataset.json);

        const ctx = document.getElementById('adminPieChart').getContext('2d');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: pieData.map(item => item.nama),
                datasets: [{
                    label: 'Kategori Pengeluaran',
                    data: pieData.map(item => item.total_nominal),
                    backgroundColor: [
                        '#5f76e8', '#ff4f70', '#01caf1', '#ffc107', '#4caf50', '#9c27b0', '#795548'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                return `${label}: Rp${value.toLocaleString('id-ID')}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
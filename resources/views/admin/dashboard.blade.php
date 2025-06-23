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

    <div class="row mt-4 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Transaksi per Bulan</h4>
                    <div id="morris-admin-bar-chart"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 ">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title">Kategori Pengeluaran</h4>
                    <div id="admin-kategori-data" data-json='@json($adminKategoriChart)'></div>
                    <div class="mt-3">
                        <canvas id="adminPieChart" height="250"></canvas>
                    </div>
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

        // Initialize pie chart
        const ctx = document.getElementById('adminPieChart').getContext('2d');
        const adminKategoriData = JSON.parse(document.getElementById('admin-kategori-data').dataset.json);
        
        const pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: adminKategoriData.map(item => item.nama),
                datasets: [{
                    data: adminKategoriData.map(item => parseFloat(item.total_nominal)),
                    backgroundColor: [
                        '#00c292', '#f44336', '#2196f3', '#9c27b0', '#ff9800',
                        '#03a9f4', '#4caf50', '#e91e63', '#3f51b5', '#2196f3'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 20,
                        bottom: 20,
                        left: 20,
                        right: 20
                    }
                }
            }
        });

        // Update chart size based on container
        function updateChartSize() {
            const container = document.querySelector('.card-body');
            if (container) {
                const width = container.clientWidth;
                pieChart.canvas.width = width;
                pieChart.canvas.height = width * 0.7; // 70% of width
                pieChart.update();
            }
        }

        // Add resize listener
        window.addEventListener('resize', updateChartSize);
        updateChartSize();
    });
</script>
@endif
@endsection
@extends('layouts.default')

@section('content')

<div class="container-fluid">
    <!-- Title -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h4 class="mb-0">Detail Kategori</h4>
        <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <!-- Detail Kategori -->
    <div class="card mb-4">
        <div class="card-header">
            <strong>Informasi Kategori</strong>
        </div>
        <div class="card-body">
            <p><strong>Nama:</strong> {{ $kategori->nama }}</p>
            <p><strong>Tipe:</strong>
            <span class="badge px-3 py-2 rounded-pill {{ $kategori->tipe === 'pemasukan' ? 'bg-success text-white' : 'bg-danger text-white' }}">
                    {{ ucfirst($kategori->tipe) }}
            </span></p>
        </div>
    </div>

    <!-- Transaksi yang Terkait -->
    <div class="card">
        <div class="card-header">
            <strong>Daftar Transaksi Terkait</strong>
        </div>
        <div class="card-body table-responsive">
            @if ($kategori->transaksi->isEmpty())
            <p class="text-muted">Tidak ada transaksi untuk kategori ini.</p>
            @else
            <table class="table table-striped">
                <thead >
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori->transaksi as $index => $transaksi)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                        <td>Rp{{ number_format($transaksi->nominal, 0, ',', '.') }}</td>
                        <td>{{ $transaksi->keterangan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
@endsection
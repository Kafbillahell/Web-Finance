@extends('layouts.default')

@section('content')
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-7 align-self-center">
            <h4 class="page-title text-truncate text-dark font-weight-medium mb-1">Dashboard</h4>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        {{-- Card Dompet --}}
        <div class="col-md-6 col-lg-4">
            <div class="card shadow rounded-3">
                <div class="card-body">
                    <h5 class="card-title mb-3">Saldo Dompet</h5>

                    @foreach($dompets as $dompet)
                        <div class="mb-3 p-3 rounded border bg-light">
                            <h6 class="mb-1">{{ $dompet->nama }}</h6>
                            <h4 class="text-success mb-0">Rp {{ number_format($dompet->saldo, 0, ',', '.') }}</h4>
                        </div>
                    @endforeach

                    @if($dompets->isEmpty())
                        <div class="alert alert-warning mb-0">
                            Belum ada dompet terdaftar.
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Buat Laporan - Karhutla Community')

@section('content')
    <div class="page-head">
        <div>
            <a class="back" href="{{ route('reports.index') }}">&larr; Kembali ke daftar laporan</a>
            <h1>Buat Laporan</h1>
            <p class="subtitle">Isi informasi indikasi kebakaran sesuai kondisi di lapangan.</p>
        </div>
    </div>

    @include('reports._form', [
        'action' => route('reports.store'),
        'method' => 'POST',
        'report' => null,
    ])
@endsection

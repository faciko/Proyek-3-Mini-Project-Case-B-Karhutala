@extends('layouts.app')

@section('title', 'Edit Laporan - Karhutla Community')

@section('content')
    <div class="page-head">
        <div>
            <a class="back" href="{{ route('reports.show', $report) }}">&larr; Kembali ke detail laporan</a>
            <h1>Edit Laporan</h1>
            <p class="subtitle">Perbarui informasi laporan selama status masih submitted.</p>
        </div>
    </div>

    @include('reports._form', [
        'action' => route('reports.update', $report),
        'method' => 'PATCH',
        'report' => $report,
    ])
@endsection

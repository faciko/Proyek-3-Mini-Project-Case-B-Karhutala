@extends('layouts.app')

@section('title', 'Daftar Laporan - Karhutla Community')

@section('content')
    <div class="page-head">
        <div>
            <h1>Laporan</h1>
            <p class="subtitle">
                @if (auth()->user()->role === 'reporter')
                    Laporan indikasi kebakaran yang kamu buat.
                @else
                    Semua laporan yang masuk dari pelapor.
                @endif
            </p>
        </div>

        @if (auth()->user()->role === 'reporter')
            <a class="btn" href="{{ route('reports.create') }}">Buat laporan</a>
        @endif
    </div>

    @if ($reports->isEmpty())
        <div class="card empty">
            <p>Belum ada laporan yang bisa ditampilkan.</p>
            @if (auth()->user()->role === 'reporter')
                <a class="btn" href="{{ route('reports.create') }}">Buat laporan pertama</a>
            @endif
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Wilayah</th>
                        <th>Pelapor</th>
                        <th>Waktu Kejadian</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td><strong>{{ $report->title }}</strong></td>
                            <td>{{ $report->area?->name ?? '-' }}</td>
                            <td>{{ $report->reporter?->name ?? '-' }}</td>
                            <td>{{ $report->occurred_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td>
                                <span class="status status-{{ $report->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $report->status)) }}
                                </span>
                            </td>
                            <td><a href="{{ route('reports.show', $report) }}">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($reports->hasPages())
            <div class="pager">
                @if ($reports->onFirstPage())
                    <span class="disabled">Sebelumnya</span>
                @else
                    <a href="{{ $reports->previousPageUrl() }}">Sebelumnya</a>
                @endif

                <span>Halaman {{ $reports->currentPage() }} dari {{ $reports->lastPage() }}</span>

                @if ($reports->hasMorePages())
                    <a href="{{ $reports->nextPageUrl() }}">Berikutnya</a>
                @else
                    <span class="disabled">Berikutnya</span>
                @endif
            </div>
        @endif
    @endif
@endsection

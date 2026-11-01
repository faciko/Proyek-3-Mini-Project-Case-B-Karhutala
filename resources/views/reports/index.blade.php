@extends('layouts.app')

@section('title', 'Daftar Laporan - Karhutla Community')

@section('content')
    @php
        $hasFilters = request()->filled('q')
            || request()->filled('area_id')
            || request()->filled('status');
    @endphp

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

    <form method="GET" action="{{ route('reports.index') }}" class="card filter-card">
        <div class="filter-grid">
            <div class="field">
                <label for="q">Cari judul</label>
                <input id="q" type="search" name="q" maxlength="100"
                       value="{{ request('q') }}" placeholder="Cari judul laporan">
            </div>

            <div class="field">
                <label for="filter-area">Wilayah</label>
                <select id="filter-area" name="area_id">
                    <option value="">Semua wilayah</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}"
                            @selected((string) request('area_id') === (string) $area->id)>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="filter-status">Status</label>
                <select id="filter-status" name="status">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn">Terapkan</button>
                @if ($hasFilters)
                    <a class="btn btn-secondary" href="{{ route('reports.index') }}">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <section class="card area-summary-card">
        <h2>Ringkasan wilayah</h2>
        <p class="subtitle" style="margin-bottom: 14px;">
            Pilih wilayah untuk melihat jumlah laporan aktif tanpa memuat ulang halaman.
        </p>
        @include('reports._area-select')
    </section>

    @if ($reports->isEmpty())
        <div class="card empty">
            @if ($hasFilters)
                <p>Tidak ada laporan yang cocok dengan filter.</p>
                <a class="btn btn-secondary" href="{{ route('reports.index') }}">Reset filter</a>
            @else
                <p>Belum ada laporan yang bisa ditampilkan.</p>
                @if (auth()->user()->role === 'reporter')
                    <a class="btn" href="{{ route('reports.create') }}">Buat laporan pertama</a>
                @endif
            @endif
        </div>
    @else
        <p class="result-count">
            Menampilkan {{ $reports->firstItem() }}–{{ $reports->lastItem() }}
            dari {{ $reports->total() }} laporan.
        </p>

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
                                    {{ $statuses[$report->status] ?? ucfirst(str_replace('_', ' ', $report->status)) }}
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

@push('scripts')
    <script src="{{ asset('js/area-summary.js') }}" defer></script>
@endpush

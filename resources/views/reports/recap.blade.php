@extends('layouts.app')

@section('title', 'Rekap Laporan - Karhutla Community')

@section('content')
    <div class="page-head">
        <div>
            <h1>Rekap Laporan</h1>
            <p class="subtitle">Ringkasan data laporan berdasarkan wilayah yang dipilih.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('reports.recap') }}" class="card recap-filter">
        <div class="recap-filter-row">
            <div class="field">
                <label for="recap-area">Wilayah</label>
                <select id="recap-area" name="area_id">
                    <option value="">Semua wilayah</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}"
                            @selected((string) request('area_id') === (string) $area->id)>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn">Terapkan</button>
                @if (request()->filled('area_id'))
                    <a class="btn btn-secondary" href="{{ route('reports.recap') }}">Reset</a>
                @endif
            </div>
        </div>
    </form>

    <div class="stats-grid">
        <article class="stat-card">
            <span class="stat-label">Total laporan</span>
            <strong class="stat-value">{{ $total }}</strong>
        </article>
        <article class="stat-card stat-active">
            <span class="stat-label">Laporan aktif</span>
            <strong class="stat-value">{{ $active }}</strong>
        </article>
        <article class="stat-card stat-closed">
            <span class="stat-label">Laporan selesai</span>
            <strong class="stat-value">{{ $closed }}</strong>
        </article>
    </div>

    @if ($byArea->isEmpty())
        <div class="card empty">
            <p>Belum ada laporan pada filter ini.</p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Wilayah</th>
                        <th>Jumlah Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($byArea as $row)
                        <tr>
                            <td>{{ $row->area?->name ?? '-' }}</td>
                            <td>{{ $row->report_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

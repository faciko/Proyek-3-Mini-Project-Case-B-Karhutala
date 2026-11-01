@extends('layouts.app')

@section('title', $report->title . ' - Karhutla Community')

@section('content')
    <div class="page-head">
        <div>
            <a class="back" href="{{ route('reports.index') }}">&larr; Kembali ke daftar laporan</a>
            <h1>{{ $report->title }}</h1>
            <p class="subtitle">
                Dilaporkan {{ $report->occurred_at?->format('d M Y H:i') ?? '-' }}
                oleh {{ $report->reporter?->name ?? '-' }}
            </p>
        </div>

        <div>
            <span class="status status-{{ $report->status }}">
                {{ ucfirst(str_replace('_', ' ', $report->status)) }}
            </span>

            @can('update', $report)
                <a class="btn" href="{{ route('reports.edit', $report) }}">Edit laporan</a>
            @endcan
        </div>
    </div>

    <div class="detail-grid">
        <section class="card">
            <h2>Informasi laporan</h2>
            <dl class="meta">
                <div>
                    <dt>Wilayah</dt>
                    <dd>{{ $report->area?->name ?? '-' }}</dd>
                </div>
                <div>
                    <dt>Kondisi</dt>
                    <dd>{{ ucfirst($report->condition_level) }}</dd>
                </div>
                <div>
                    <dt>Koordinat</dt>
                    <dd>{{ $report->latitude }}, {{ $report->longitude }}</dd>
                </div>
                <div>
                    <dt>Reviewer</dt>
                    <dd>{{ $report->reviewer?->name ?? 'Belum ditinjau' }}</dd>
                </div>
                <div>
                    <dt>Dibuat</dt>
                    <dd>{{ $report->created_at?->format('d M Y H:i') ?? '-' }}</dd>
                </div>
            </dl>
        </section>

        <section class="card">
            <h2>Deskripsi</h2>
            <p class="description">{!! nl2br(e($report->description)) !!}</p>

            @if ($report->evidence_path)
                <div class="evidence">
                    <strong>Bukti:</strong>
                    <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($report->evidence_path) }}"
                       target="_blank" rel="noopener">Lihat file bukti</a>
                </div>
            @endif

            @if ($report->review_note)
                <div class="note-box">
                    <strong>Catatan review</strong>
                    {{ $report->review_note }}
                </div>
            @endif

            @if ($report->forwarded_to)
                <div class="note-box">
                    <strong>Diteruskan ke</strong>
                    {{ $report->forwarded_to }}
                </div>
            @endif
        </section>
    </div>
@endsection

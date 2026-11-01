@extends('layouts.app')

@section('title', $report->title . ' - Karhutla Community')

@php
    $statusLabels = [
        'submitted' => 'Menunggu ditinjau',
        'under_review' => 'Sedang ditinjau',
        'needs_attention' => 'Perlu perhatian',
        'ready_to_forward' => 'Siap diteruskan',
        'forwarded' => 'Sudah diteruskan',
        'closed' => 'Selesai',
    ];
    $statusLabel = $statusLabels[$report->status]
        ?? ucfirst(str_replace('_', ' ', $report->status));
@endphp

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
                {{ $statusLabel }}
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

    @if (in_array(auth()->user()->role, ['monitor', 'coordinator'], true))
        <section class="card workflow">
            <h2>Ubah status</h2>

            @if ($allowedTransitions === [])
                <p class="subtitle">
                    Tidak ada transisi status yang tersedia untuk akun ini pada status
                    <strong>{{ $statusLabel }}</strong>.
                </p>
            @else
                <p class="subtitle">
                    Pilih status berikutnya. Aturan transisi dan role tetap diperiksa di server.
                </p>

                <form method="POST" action="{{ route('reports.status', $report) }}">
                    @csrf
                    @method('PATCH')

                    <div class="field">
                        <label for="status">Status berikutnya</label>
                        <select id="status" name="status" required>
                            <option value="">Pilih status</option>
                            @foreach ($allowedTransitions as $nextStatus)
                                <option value="{{ $nextStatus }}"
                                    @selected(old('status') === $nextStatus)>
                                    {{ $statusLabels[$nextStatus] ?? ucfirst(str_replace('_', ' ', $nextStatus)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    <div class="field">
                        <label for="note">Catatan review (opsional)</label>
                        <textarea id="note" name="note" maxlength="2000">{{ old('note', $report->review_note) }}</textarea>
                        @error('note') <span class="error">{{ $message }}</span> @enderror
                    </div>

                    @if (in_array('forwarded', $allowedTransitions, true))
                        <div class="field">
                            <label for="forwarded_to">Diteruskan ke</label>
                            <input id="forwarded_to" type="text" name="forwarded_to" maxlength="160"
                                   value="{{ old('forwarded_to', $report->forwarded_to) }}"
                                   placeholder="Contoh: BPBD Kabupaten">
                            @error('forwarded_to') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    <div class="form-actions">
                        <button type="submit" class="btn">Simpan status</button>
                    </div>
                </form>
            @endif
        </section>
    @endif
@endsection

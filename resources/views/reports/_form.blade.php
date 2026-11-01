@php
    $isEdit = $report !== null;
@endphp

<form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="form-card">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="field">
        <label for="title">Judul laporan</label>
        <input id="title" type="text" name="title" maxlength="120"
               value="{{ old('title', $report?->title) }}" required>
        @error('title') <span class="error">{{ $message }}</span> @enderror
    </div>

    <div class="grid-2">
        <div class="field">
            <label for="area_id">Wilayah</label>
            <select id="area_id" name="area_id" required>
                <option value="">Pilih wilayah</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}"
                        @selected((string) old('area_id', $report?->area_id) === (string) $area->id)>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            @error('area_id') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="condition_level">Tingkat kondisi</label>
            <select id="condition_level" name="condition_level" required>
                <option value="">Pilih kondisi</option>
                @foreach (['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'] as $value => $label)
                    <option value="{{ $value }}"
                        @selected(old('condition_level', $report?->condition_level) === $value)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('condition_level') <span class="error">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="field">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" maxlength="5000" required>{{ old('description', $report?->description) }}</textarea>
        @error('description') <span class="error">{{ $message }}</span> @enderror
    </div>

    <div class="field">
        <label for="occurred_at">Waktu kejadian</label>
        <input id="occurred_at" type="datetime-local" name="occurred_at"
               value="{{ old('occurred_at', $report?->occurred_at?->format('Y-m-d\TH:i')) }}" required>
        @error('occurred_at') <span class="error">{{ $message }}</span> @enderror
    </div>

    <div class="grid-2">
        <div class="field">
            <label for="latitude">Latitude</label>
            <input id="latitude" type="number" name="latitude" step="any"
                   min="-90" max="90" value="{{ old('latitude', $report?->latitude) }}" required>
            @error('latitude') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="field">
            <label for="longitude">Longitude</label>
            <input id="longitude" type="number" name="longitude" step="any"
                   min="-180" max="180" value="{{ old('longitude', $report?->longitude) }}" required>
            @error('longitude') <span class="error">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="field">
        <label for="evidence">Bukti (opsional)</label>
        <input id="evidence" type="file" name="evidence" accept=".jpg,.jpeg,.png,.pdf">
        <p class="hint">Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.</p>
        @error('evidence') <span class="error">{{ $message }}</span> @enderror

        @if ($isEdit && $report->evidence_path)
            <p class="hint">
                Bukti saat ini:
                <a href="{{ route('reports.evidence', $report) }}"
                   target="_blank" rel="noopener">lihat file</a>.
                Biarkan input kosong jika tidak ingin mengganti bukti.
            </p>
        @endif
    </div>

    <div class="form-actions">
        <button type="submit" class="btn">
            {{ $isEdit ? 'Perbarui laporan' : 'Simpan laporan' }}
        </button>
        <a class="btn btn-secondary"
           href="{{ $isEdit ? route('reports.show', $report) : route('reports.index') }}">
            Batal
        </a>
    </div>
</form>

<div class="field area-summary-field">
    <label for="area-select">Wilayah</label>
    <select id="area-select"
            data-url-template="{{ route('areas.summary', ['area' => '__AREA__']) }}">
        <option value="">Pilih wilayah</option>
        @foreach ($areas as $area)
            <option value="{{ $area->id }}">{{ $area->name }}</option>
        @endforeach
    </select>
    <p id="area-summary" class="summary-output" role="status" aria-live="polite"></p>
</div>

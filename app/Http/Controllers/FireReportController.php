<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFireReportRequest;
use App\Http\Requests\UpdateFireReportRequest;
use App\Models\Area;
use App\Models\FireReport;
use App\Services\ReportWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FireReportController extends Controller
{
    /**
     * Bukti disimpan di disk privat, lalu disajikan lewat route ber-authorization.
     */
    private const EVIDENCE_DISK = 'local';

    /**
     * Daftar laporan. Pelapor hanya melihat laporan miliknya.
     */
    public function index(Request $request): View
    {
        $query = FireReport::query()->with(['area', 'reporter']);

        if ($request->user()->role === 'reporter') {
            $query->where('user_id', $request->user()->id);
        }

        $reports = $query->latest()->paginate(10);

        return view('reports.index', compact('reports'));
    }

    /**
     * Tampilkan form pembuatan laporan.
     */
    public function create(): View
    {
        abort_unless(auth()->user()->role === 'reporter', 403);

        $areas = Area::orderBy('name')->get();

        return view('reports.create', [
            'areas' => $areas,
            'report' => null,
        ]);
    }

    /**
     * Simpan laporan baru milik user yang sedang login.
     */
    public function store(StoreFireReportRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $evidence = $request->file('evidence');
        unset($data['evidence']);

        if ($evidence) {
            $data['evidence_path'] = $evidence->store('report-evidence', self::EVIDENCE_DISK);
        }

        $request->user()->reports()->create($data);

        return to_route('reports.index')
            ->with('status', 'Laporan berhasil dibuat.');
    }

    /**
     * Detail satu laporan. Route model binding mengubah {report} menjadi model.
     */
    public function show(
        FireReport $report,
        ReportWorkflowService $workflow
    ): View {
        $this->authorize('view', $report);

        $report->load(['area', 'reporter', 'reviewer']);

        $allowedTransitions = $workflow->allowedTransitions(
            $report,
            auth()->user()
        );

        return view('reports.show', compact('report', 'allowedTransitions'));
    }

    /**
     * Tampilkan form edit laporan.
     */
    public function edit(FireReport $report): View
    {
        $this->authorize('update', $report);

        $areas = Area::orderBy('name')->get();

        return view('reports.edit', compact('areas', 'report'));
    }

    /**
     * Perbarui laporan milik pelapor.
     */
    public function update(
        UpdateFireReportRequest $request,
        FireReport $report
    ): RedirectResponse {
        $this->authorize('update', $report);

        $data = $request->validated();
        $evidence = $request->file('evidence');
        unset($data['evidence']);

        $oldPath = $report->evidence_path;

        if ($evidence) {
            $data['evidence_path'] = $evidence->store('report-evidence', self::EVIDENCE_DISK);
        }

        $report->update($data);

        if ($evidence && $oldPath) {
            Storage::disk(self::EVIDENCE_DISK)->delete($oldPath);
        }

        return to_route('reports.show', $report)
            ->with('status', 'Laporan berhasil diperbarui.');
    }
}

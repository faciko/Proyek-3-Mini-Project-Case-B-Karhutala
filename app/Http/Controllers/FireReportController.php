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
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FireReportController extends Controller
{

    private const EVIDENCE_DISK = 'local';

    public function index(Request $request): View
    {
        $statuses = [
            'submitted' => 'Menunggu ditinjau',
            'under_review' => 'Sedang ditinjau',
            'needs_attention' => 'Perlu perhatian',
            'ready_to_forward' => 'Siap diteruskan',
            'forwarded' => 'Sudah diteruskan',
            'closed' => 'Selesai',
        ];

        $data = $request->validate([
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
            'status' => ['nullable', Rule::in(array_keys($statuses))],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = FireReport::query()->with(['area', 'reporter']);

        if ($request->user()->role === 'reporter') {
            $query->where('user_id', $request->user()->id);
        }

        $reports = $query
            ->when($data['area_id'] ?? null, fn ($q, $id) =>
                $q->where('area_id', $id))
            ->when($data['status'] ?? null, fn ($q, $status) =>
                $q->where('status', $status))
            ->when($data['q'] ?? null, fn ($q, $term) =>
                $q->where('title', 'like', '%' . $term . '%'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $areas = Area::orderBy('name')->get();

        return view('reports.index', compact('reports', 'areas', 'statuses'));
    }

    public function create(): View
    {
        abort_unless(auth()->user()->role === 'reporter', 403);

        $areas = Area::orderBy('name')->get();

        return view('reports.create', [
            'areas' => $areas,
            'report' => null,
        ]);
    }

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

    public function edit(FireReport $report): View
    {
        $this->authorize('update', $report);

        $areas = Area::orderBy('name')->get();

        return view('reports.edit', compact('areas', 'report'));
    }

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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReportStatusRequest;
use App\Models\FireReport;
use App\Services\ReportWorkflowService;
use Illuminate\Http\RedirectResponse;

class FireReportStatusController extends Controller
{
    /**
     * Ubah status laporan melalui service workflow.
     */
    public function update(
        UpdateReportStatusRequest $request,
        FireReport $report,
        ReportWorkflowService $workflow
    ): RedirectResponse {
        $data = $request->validated();

        $workflow->transition(
            $report,
            $request->user(),
            $data['status'],
            $data['note'] ?? null,
            $data['forwarded_to'] ?? null
        );

        return back()->with('status', 'Status diperbarui.');
    }
}

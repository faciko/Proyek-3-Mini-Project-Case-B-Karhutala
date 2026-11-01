<?php

namespace App\Observers;

use App\Models\FireReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FireReportObserver
{
    public function updated(FireReport $report): void
    {
        if (! $report->wasChanged('status')) {
            return;
        }

        Log::info('fire_report.status_changed', [
            'report_id' => $report->id,
            'from' => $report->getOriginal('status'),
            'to' => $report->status,
            'actor_id' => Auth::id(),
        ]);
    }
}

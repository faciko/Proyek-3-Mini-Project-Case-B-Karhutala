<?php

namespace App\Http\Controllers;

use App\Models\FireReport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FireReportEvidenceController extends Controller
{

    public function __invoke(FireReport $report): StreamedResponse
    {
        $this->authorize('view', $report);

        $path = $report->evidence_path;

        abort_unless(
            $path && Storage::disk('local')->exists($path),
            404,
            'File bukti tidak ditemukan.'
        );

        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $downloadName = Str::slug($report->title) . ($extension ? '.' . $extension : '');

        return Storage::disk('local')->response($path, $downloadName);
    }
}

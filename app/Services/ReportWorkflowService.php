<?php

namespace App\Services;

use App\Models\FireReport;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class ReportWorkflowService
{

    private const TRANSITIONS = [
        'submitted' => ['under_review'],
        'under_review' => ['needs_attention', 'ready_to_forward'],
        'needs_attention' => ['under_review', 'ready_to_forward'],
        'ready_to_forward' => ['forwarded'],
        'forwarded' => ['closed'],
        'closed' => [],
    ];

    public function transition(
        FireReport $report,
        User $actor,
        string $next,
        ?string $note = null,
        ?string $forwardedTo = null
    ): void {
        $allowed = self::TRANSITIONS[$report->status] ?? [];

        abort_unless(in_array($next, $allowed, true), 422);

        abort_unless($this->actorCanPerform($next, $actor), 403);

        if ($next === 'forwarded' && blank($forwardedTo)) {
            throw ValidationException::withMessages([
                'forwarded_to' => 'Tujuan penerusan wajib dicatat.',
            ]);
        }

        $report->status = $next;
        $report->reviewer_id = $actor->role === 'monitor'
            ? $actor->id
            : $report->reviewer_id;
        $report->review_note = $note ?? $report->review_note;
        $report->forwarded_to = $forwardedTo ?? $report->forwarded_to;
        $report->save();
    }

    public function allowedTransitions(FireReport $report, User $actor): array
    {
        $allowed = self::TRANSITIONS[$report->status] ?? [];

        return array_values(array_filter(
            $allowed,
            fn (string $next): bool => $this->actorCanPerform($next, $actor)
        ));
    }

    private function actorCanPerform(string $next, User $actor): bool
    {
        $monitorAction = in_array($next, [
            'under_review', 'needs_attention', 'ready_to_forward',
        ], true);

        return $monitorAction
            ? $actor->role === 'monitor'
            : $actor->role === 'coordinator';
    }
}

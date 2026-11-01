<?php

namespace App\Policies;

use App\Models\FireReport;
use App\Models\User;

class FireReportPolicy
{

    public function view(User $user, FireReport $report): bool
    {
        if (in_array($user->role, ['monitor', 'coordinator'], true)) {
            return true;
        }

        return $user->role === 'reporter'
            && $report->user_id === $user->id;
    }

    public function update(User $user, FireReport $report): bool
    {
        return $user->role === 'reporter'
            && $report->user_id === $user->id
            && $report->status === 'submitted';
    }
}

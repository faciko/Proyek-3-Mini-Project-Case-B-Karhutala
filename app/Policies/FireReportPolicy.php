<?php

namespace App\Policies;

use App\Models\FireReport;
use App\Models\User;

class FireReportPolicy
{
    /**
     * Monitor dan koordinator boleh melihat semua laporan.
     * Pelapor hanya boleh melihat laporan miliknya sendiri.
     */
    public function view(User $user, FireReport $report): bool
    {
        if (in_array($user->role, ['monitor', 'coordinator'], true)) {
            return true;
        }

        return $user->role === 'reporter'
            && $report->user_id === $user->id;
    }

    /**
     * Pelapor hanya boleh mengubah laporan miliknya saat status submitted.
     */
    public function update(User $user, FireReport $report): bool
    {
        return $user->role === 'reporter'
            && $report->user_id === $user->id
            && $report->status === 'submitted';
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\FireReport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportDashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $data = $request->validate([
            'area_id' => ['nullable', 'integer', 'exists:areas,id'],
        ]);

        $base = FireReport::query()
            ->when($data['area_id'] ?? null, fn ($q, $id) =>
                $q->where('area_id', $id));

        $total = (clone $base)->count();
        $active = (clone $base)
            ->whereNotIn('status', ['closed'])
            ->count();
        $closed = (clone $base)->where('status', 'closed')->count();

        $byArea = (clone $base)
            ->selectRaw('area_id, count(*) as report_count')
            ->with('area')
            ->groupBy('area_id')
            ->orderByDesc('report_count')
            ->get();

        $areas = Area::orderBy('name')->get();

        return view('reports.recap', compact(
            'total', 'active', 'closed', 'byArea', 'areas'
        ));
    }
}

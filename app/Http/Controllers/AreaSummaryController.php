<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\JsonResponse;

class AreaSummaryController extends Controller
{

    public function __invoke(Area $area): JsonResponse
    {
        $active = $area->reports()
            ->whereNotIn('status', ['closed'])
            ->count();

        return response()->json([
            'area' => $area->name,
            'active_reports_count' => $active,
        ]);
    }
}

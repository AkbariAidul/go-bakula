<?php

namespace App\Http\Controllers;

use App\Domains\Reports\Models\Report;
use App\Domains\Departments\Models\Department;
use App\Domains\Categories\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $stats = [
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'in_progress_reports' => Report::where('status', 'in_progress')->count(),
            'completed_reports' => Report::where('status', 'completed')->count(),
            'urgent_reports' => Report::where('is_urgent', true)->count(),
        ];

        // Filter by department for admin_dinas
        if ($user->hasRole('admin_dinas')) {
            // Get department IDs from categories assigned to this admin
            // For now, we'll show all reports
            $recentReports = Report::with(['user', 'category', 'department'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $recentReports = Report::with(['user', 'category', 'department'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        }

        $heatmapData = Report::selectRaw('latitude, longitude, COUNT(*) as count')
            ->groupBy('latitude', 'longitude')
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentReports' => $recentReports,
            'heatmapData' => $heatmapData,
        ]);
    }
}

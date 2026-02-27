<?php

namespace App\Http\Controllers\Reports;

use App\Domains\Reports\Models\Report;
use App\Domains\Reports\Repositories\ReportRepository;
use App\Domains\Reports\Services\ReportService;
use App\Domains\Categories\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function __construct(
        protected ReportRepository $repository,
        protected ReportService $service
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'category_id', 'department_id', 'is_urgent']);
        $reports = $this->repository->getAllPaginated(15, $filters);

        return Inertia::render('Reports/Index', [
            'reports' => $reports,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        $categories = Category::with('department')
            ->where('is_active', true)
            ->get();

        return Inertia::render('Reports/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|image|max:5120',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string',
        ]);

        $category = Category::find($validated['category_id']);
        $validated['department_id'] = $category->department_id;
        $validated['user_id'] = $request->user()->id;

        $report = $this->service->createReport($validated, $request->file('photo'));

        return redirect()->route('reports.show', $report->id)
            ->with('success', 'Laporan berhasil dibuat dan akan segera diverifikasi.');
    }

    public function show(Report $report)
    {
        $report->load(['user', 'category', 'department', 'upvotes.user']);

        return Inertia::render('Reports/Show', [
            'report' => $report,
        ]);
    }

    public function myReports(Request $request)
    {
        $reports = $this->repository->getByUser($request->user()->id);

        return Inertia::render('Reports/MyReports', [
            'reports' => $reports,
        ]);
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,in_progress,completed,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $this->service->updateStatus(
            $report,
            $validated['status'],
            $validated['admin_notes'] ?? null,
            $request->user()->id
        );

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    public function uploadCompletion(Request $request, Report $report)
    {
        $request->validate([
            'completion_photo' => 'required|image|max:5120',
        ]);

        $this->service->uploadCompletionPhoto($report, $request->file('completion_photo'));
        $this->service->updateStatus($report, 'completed', null, $request->user()->id);

        return back()->with('success', 'Foto penyelesaian berhasil diunggah.');
    }

    public function destroy(Request $request, Report $report)
    {
        // Check if user owns the report or is admin
        if ($report->user_id !== $request->user()->id && !$request->user()->hasRole(['super_admin', 'admin_dinas'])) {
            abort(403, 'Unauthorized action.');
        }
        
        $this->service->deleteReport($report);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}

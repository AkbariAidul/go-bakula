<?php

namespace App\Domains\Reports\Services;

use App\Domains\Reports\Models\Report;
use App\Domains\Reports\Repositories\ReportRepository;
use App\Domains\Gamification\Services\GamificationService;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function __construct(
        protected ReportRepository $repository,
        protected GamificationService $gamificationService
    ) {}

    public function createReport(array $data, $photoFile): Report
    {
        // Upload photo
        $photoPath = $photoFile->store('reports', 'public');
        $data['photo_path'] = $photoPath;

        // Create report
        $report = $this->repository->create($data);

        return $report;
    }

    public function updateStatus(Report $report, string $status, ?string $notes = null, ?int $userId = null): bool
    {
        $updateData = [
            'status' => $status,
            'admin_notes' => $notes,
        ];

        if ($status === 'verified') {
            $updateData['verified_at'] = now();
            $updateData['verified_by'] = $userId;
        }

        if ($status === 'completed') {
            $updateData['completed_at'] = now();
            $updateData['completed_by'] = $userId;
            
            // Award points to reporter
            $this->gamificationService->awardPointsForCompletedReport($report->user_id);
        }

        return $this->repository->update($report, $updateData);
    }

    public function uploadCompletionPhoto(Report $report, $photoFile): bool
    {
        $photoPath = $photoFile->store('reports/completions', 'public');
        
        return $this->repository->update($report, [
            'completion_photo_path' => $photoPath,
        ]);
    }

    public function checkAndMarkUrgent(Report $report): void
    {
        if ($report->upvotes_count >= 50 && !$report->is_urgent) {
            $this->repository->update($report, ['is_urgent' => true]);
        }
    }

    public function deleteReport(Report $report): bool
    {
        // Delete photos
        if ($report->photo_path) {
            Storage::disk('public')->delete($report->photo_path);
        }
        
        if ($report->completion_photo_path) {
            Storage::disk('public')->delete($report->completion_photo_path);
        }

        return $this->repository->delete($report);
    }
}

<?php

namespace App\Domains\Reports\Services;

use App\Domains\Reports\Models\Report;
use App\Domains\Reports\Models\Upvote;
use App\Domains\Gamification\Services\GamificationService;

class UpvoteService
{
    public function __construct(
        protected GamificationService $gamificationService
    ) {}

    public function toggleUpvote(int $userId, int $reportId): array
    {
        $upvote = Upvote::where('user_id', $userId)
            ->where('report_id', $reportId)
            ->first();

        if ($upvote) {
            // Remove upvote
            $upvote->delete();
            
            $report = Report::find($reportId);
            $report->decrement('upvotes_count');
            
            // Remove point
            $this->gamificationService->removePointForUpvote($userId);
            
            return ['upvoted' => false, 'count' => $report->upvotes_count];
        } else {
            // Add upvote
            Upvote::create([
                'user_id' => $userId,
                'report_id' => $reportId,
            ]);
            
            $report = Report::find($reportId);
            $report->increment('upvotes_count');
            
            // Award point
            $this->gamificationService->awardPointForUpvote($userId);
            
            // Check if should mark as urgent
            if ($report->upvotes_count >= 50) {
                $report->update(['is_urgent' => true]);
            }
            
            return ['upvoted' => true, 'count' => $report->upvotes_count];
        }
    }

    public function hasUserUpvoted(int $userId, int $reportId): bool
    {
        return Upvote::where('user_id', $userId)
            ->where('report_id', $reportId)
            ->exists();
    }
}

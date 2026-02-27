<?php

namespace App\Domains\Gamification\Services;

use App\Domains\Gamification\Models\UserPoint;

class GamificationService
{
    const POINTS_COMPLETED_REPORT = 10;
    const POINTS_UPVOTE = 1;

    protected array $badges = [
        'Pemula' => 0,
        'Warga Peduli' => 50,
        'Pahlawan Lingkungan' => 100,
        'Guardian Kota' => 250,
        'Legend' => 500,
    ];

    public function awardPointsForCompletedReport(int $userId): void
    {
        $userPoint = $this->getUserPoint($userId);
        $userPoint->points += self::POINTS_COMPLETED_REPORT;
        $userPoint->reports_completed += 1;
        $userPoint->badge = $this->calculateBadge($userPoint->points);
        $userPoint->save();
    }

    public function awardPointForUpvote(int $userId): void
    {
        $userPoint = $this->getUserPoint($userId);
        $userPoint->points += self::POINTS_UPVOTE;
        $userPoint->upvotes_given += 1;
        $userPoint->badge = $this->calculateBadge($userPoint->points);
        $userPoint->save();
    }

    public function removePointForUpvote(int $userId): void
    {
        $userPoint = $this->getUserPoint($userId);
        $userPoint->points = max(0, $userPoint->points - self::POINTS_UPVOTE);
        $userPoint->upvotes_given = max(0, $userPoint->upvotes_given - 1);
        $userPoint->badge = $this->calculateBadge($userPoint->points);
        $userPoint->save();
    }

    public function getLeaderboard(int $limit = 10)
    {
        return UserPoint::with('user')
            ->orderBy('points', 'desc')
            ->limit($limit)
            ->get();
    }

    protected function getUserPoint(int $userId): UserPoint
    {
        return UserPoint::firstOrCreate(
            ['user_id' => $userId],
            ['points' => 0, 'badge' => 'Pemula']
        );
    }

    protected function calculateBadge(int $points): string
    {
        $badge = 'Pemula';
        
        foreach ($this->badges as $badgeName => $requiredPoints) {
            if ($points >= $requiredPoints) {
                $badge = $badgeName;
            }
        }
        
        return $badge;
    }
}

<?php

namespace App\Http\Controllers\Gamification;

use App\Domains\Gamification\Services\GamificationService;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function __construct(
        protected GamificationService $service
    ) {}

    public function index()
    {
        $leaderboard = $this->service->getLeaderboard(50);

        return Inertia::render('Gamification/Leaderboard', [
            'leaderboard' => $leaderboard,
        ]);
    }
}

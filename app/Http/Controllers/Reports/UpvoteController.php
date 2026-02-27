<?php

namespace App\Http\Controllers\Reports;

use App\Domains\Reports\Services\UpvoteService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UpvoteController extends Controller
{
    public function __construct(
        protected UpvoteService $service
    ) {}

    public function toggle(Request $request, int $reportId)
    {
        $result = $this->service->toggleUpvote($request->user()->id, $reportId);

        return response()->json($result);
    }
}

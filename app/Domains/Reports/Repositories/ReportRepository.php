<?php

namespace App\Domains\Reports\Repositories;

use App\Domains\Reports\Models\Report;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReportRepository
{
    public function findById(int $id): ?Report
    {
        return Report::with(['user', 'category', 'department', 'upvotes'])->find($id);
    }

    public function getAllPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Report::with(['user', 'category', 'department'])
            ->orderBy('created_at', 'desc');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (isset($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['is_urgent'])) {
            $query->where('is_urgent', $filters['is_urgent']);
        }

        return $query->paginate($perPage);
    }

    public function getByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Report::with(['category', 'department'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    public function create(array $data): Report
    {
        return Report::create($data);
    }

    public function update(Report $report, array $data): bool
    {
        return $report->update($data);
    }

    public function delete(Report $report): bool
    {
        return $report->delete();
    }

    public function getHeatmapData(): Collection
    {
        return Report::selectRaw('latitude, longitude, COUNT(*) as count')
            ->groupBy('latitude', 'longitude')
            ->get();
    }
}

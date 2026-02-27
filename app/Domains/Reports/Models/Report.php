<?php

namespace App\Domains\Reports\Models;

use App\Domains\Categories\Models\Category;
use App\Domains\Departments\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'department_id',
        'title',
        'description',
        'photo_path',
        'latitude',
        'longitude',
        'address',
        'status',
        'admin_notes',
        'completion_photo_path',
        'verified_at',
        'completed_at',
        'verified_by',
        'completed_by',
        'upvotes_count',
        'is_urgent',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'verified_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_urgent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(Upvote::class);
    }
}

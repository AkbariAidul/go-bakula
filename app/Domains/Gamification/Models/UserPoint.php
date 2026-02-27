<?php

namespace App\Domains\Gamification\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'badge',
        'reports_completed',
        'upvotes_given',
    ];

    protected $casts = [
        'points' => 'integer',
        'reports_completed' => 'integer',
        'upvotes_given' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

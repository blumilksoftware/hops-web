<?php

declare(strict_types=1);

namespace HopsWeb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property array $query
 * @property ?array $response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class HopQuery extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "query",
        "response",
    ];
    protected $casts = [
        "query" => "array",
        "response" => "array",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

declare(strict_types=1);

namespace HopsWeb\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $agenda_id
 * @property array $parameters
 * @property ?array $response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Agenda $agenda
 */
class AgendaResult extends Model
{
    use HasFactory;

    protected $fillable = [
        "agenda_id",
        "parameters",
        "response",
    ];
    protected $casts = [
        "parameters" => "array",
        "response" => "array",
    ];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }
}

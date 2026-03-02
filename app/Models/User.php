<?php

declare(strict_types=1);

namespace HopsWeb\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 * @property Carbon $email_verified_at
 * @property bool $is_admin
 * @property bool $is_team_member
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, Agenda> $agendas
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        "name",
        "email",
        "password",
        "is_admin",
        "is_team_member",
    ];
    protected $hidden = [
        "password",
        "remember_token",
    ];

    public function agendas(): HasMany
    {
        return $this->hasMany(Agenda::class);
    }

    protected function casts(): array
    {
        return [
            "email_verified_at" => "datetime",
            "password" => "hashed",
            "is_admin" => "boolean",
            "is_team_member" => "boolean",
        ];
    }
}

<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'username',
    'email',
    'password',
    'role',
    'opd_name',
    'kecamatan_id',
    'phone',
    'avatar_path',
    'is_active',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_SUPERADMIN = 'superadmin';

    public const ROLE_ADMIN = 'admin_kabupaten';

    public const ROLE_OPD = 'opd';

    public const ROLE_KECAMATAN = 'operator_kecamatan';

    public const ROLE_BPS = 'bps';

    public const ROLE_PUBLIK = 'publik';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public static function manageableRoles(): array
    {
        return [
            self::ROLE_OPD,
            self::ROLE_ADMIN,
            self::ROLE_KECAMATAN,
            self::ROLE_BPS,
        ];
    }

    public static function roleOptions(): array
    {
        return [
            self::ROLE_OPD => 'Pejabat OPD',
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_KECAMATAN => 'User per Wilayah Kecamatan',
            self::ROLE_BPS => 'BPS',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::roleOptions()[$this->role] ?? ucfirst(str_replace('_', ' ', $this->role));
    }

    public function canManageUsers(): bool
    {
        return in_array($this->role, [self::ROLE_SUPERADMIN, self::ROLE_ADMIN], true);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }
}

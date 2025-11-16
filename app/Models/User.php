<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // ← enables soft deletes

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Mass-assignable attributes.
     * These are the columns you allow to be filled via arrays (e.g., User::create([...])).
     * Keep this list tight for security.
     */
    protected $fillable = [
        'name',              // Laravel's default
        'email',
        'password',
        'is_active',
        'is_protected',
        'last_login_at',
        'password_changed_at',
        'email_verified_at', // can be set by verification workflow
    ];

    /**
     * Attributes hidden when serializing to arrays/JSON.
     * Never expose raw passwords or tokens via API.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute type casting.
     * - 'hashed' automatically hashes plain strings assigned to 'password'.
     * - timestamps cast to Carbon instances for date math.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'is_active' => 'boolean',
            'is_protected' => 'boolean',
            'password' => 'hashed', // auto-hash when assigning
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->is_protected) {
                throw new \RuntimeException('This user account is protected and cannot be deleted.');
            }
        });

        static::forceDeleting(function (User $user) {
            if ($user->is_protected) {
                throw new \RuntimeException('This user account is protected and cannot be force deleted.');
            }
        });
    }

    /**
     * 🔗 Relationships
     * A user can have many roles via the user_role pivot table.
     * (Ensure you created that pivot in your migrations.)
     */
    public function roles()
    {
        // table name explicitly set to match your pivot: user_role
        return $this->belongsToMany(Role::class, 'user_role');
    }

    /**
     * Convenience checks
     * Use these to guard routes/controllers easily.
     */
    public function hasRole(string $key): bool
    {
        return $this->roles()->where('key', $key)->exists();
    }

    public function hasAnyRole(array $keys): bool
    {
        return $this->roles()->whereIn('key', $keys)->exists();
    }
}

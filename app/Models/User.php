<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'parent_id',
        'phone_number',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'phone_number_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function Profile(): HasOne
    {
        return $this->hasOne(Profile::class, 'user_id', 'id');
    }

    public function Projects(): HasMany
    {
        return $this->hasMany(Project::class, 'user_id', 'id');
    }

    public function Parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function Children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function Roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function FullName(): string
    {
        $profile = $this->Profile;
        return translate($profile->first_name) . ' ' . translate($profile->last_name);
    }

    public function HasRole($role): bool
    {
        return in_array($role, $this->Roles->pluck('name')->toArray());
    }

    public function HasPermission($permission): bool
    {
        $permissions = collect();
        foreach ($this->Roles as $role) {
            $permissions->push($role->Permissions);
        }
        return in_array($permission, $permissions->pluck('name')->toArray());
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function Permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function Users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function HasPermission($permission): bool
    {
        $field = is_numeric($permission) ? 'id' : 'name';
        return in_array($permission, $this->Permissions->pluck($field)->toArray());
    }

    public function Translate(): void
    {
        foreach ($this->Permissions as $permission) {
            $permission->name = translate($permission->name);
        }
    }
}

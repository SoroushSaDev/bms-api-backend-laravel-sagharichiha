<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Project $project) {
            if (is_null($project->user_id)) {
                $project->user_id = auth()->check() ? auth()->id() : null;
            }
        });
        static::updating(function (Project $project) {
            if (is_null($project->user_id)) {
                $project->user_id = auth()->check() ? auth()->id() : null;
            }
        });
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function City(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }

    public function Devices(): HasMany
    {
        return $this->hasMany(Device::class, 'project_id', 'id');
    }

    public function HasDevice($device): int
    {
        $devices = Device::where('parent_id', $device)->where('project_id', $this->id)->get();
        return count($devices);
    }

    public function Translate(): void
    {
        TranslateAll($this, ['name', 'description', 'address']);
        $this->City->name = translate($this->City->name);
        $this->Devices->map(function ($device) {
            $device->Translate();
        });
    }
}

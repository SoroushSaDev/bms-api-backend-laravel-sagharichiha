<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Device $device) {
            $device->user_id = auth()->check() ? auth()->id() : null;
        });
        static::updating(function (Device $device) {
            $device->user_id = auth()->check() ? auth()->id() : null;
        });
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Registers(): HasMany
    {
        return $this->hasMany(Register::class, 'device_id', 'id');
    }

    public function Translate(): void
    {
        TranslateAll($this, ['name', 'type', 'brand', 'model', 'description']);
        $this->Registers->map(function ($register) {
            $register->Translate();
        });
    }
}

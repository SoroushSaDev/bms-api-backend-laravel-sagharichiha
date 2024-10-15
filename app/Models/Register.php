<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Register $register) {
            $register->user_id = auth()->check() ? auth()->id() : null;
        });
        static::updating(function (Register $register) {
            $register->user_id = auth()->check() ? auth()->id() : null;
        });
    }

    const Types = [
        'none' => 'None',
        'int' => 'Integer',
        'bool' => 'Boolean',
    ];

    public function Device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function Parent(): BelongsTo
    {
        return $this->belongsTo(Register::class, 'parent_id', 'id');
    }

    public function Children(): HasMany
    {
        return $this->hasMany(Register::class, 'parent_id', 'id');
    }

    public function Translate(): void
    {
        TranslateAll($this, ['unit', 'input', 'output']);
    }
}

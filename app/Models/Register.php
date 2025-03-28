<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Register extends Model
{
    use SoftDeletes;

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

    public function ChartLogs($limit = null)
    {
        $logs = Log::where('loggable_type', Register::class)->where('loggable_id', $this->id);
        if (!is_null($limit)) {
            $logs = $logs->limit($limit);
        }
        return $logs->get();
    }

    public function Translate(): void
    {
        TranslateAll($this, ['unit', 'input', 'output']);
    }
}

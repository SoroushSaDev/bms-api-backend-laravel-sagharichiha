<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'binary',
        'integer',
        'string',
        'char',
        'float',
        'long',
    ];
}

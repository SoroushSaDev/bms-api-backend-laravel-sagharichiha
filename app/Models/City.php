<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (City $city) {
            $city->user_id = auth()->check() ? auth()->id() : null;
        });
        static::updating(function (City $city) {
            $city->user_id = auth()->check() ? auth()->id() : null;
        });
    }

    public function Country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function Translate(): void
    {
        $this->name = translate($this->name);
        $this->Country->name = translate($this->Country->en_name);
    }

    public static function CanCreate(): bool
    {
        return auth()->user()->type != 'user' || auth()->user()->HasPermission( 'create_cities');
    }

    public function CanEdit(): bool
    {
        return auth()->user()->type == 'admin' || $this->user_id == auth()->id() || auth()->user()->HasPermission('edit_cities');
    }

    public static function CanShow(): bool
    {
        return auth()->user()->type != 'user' || auth()->user()->HasPermission('show_cities');
    }

    public function CanDelete(): bool
    {
        return auth()->user()->type == 'admin' || auth()->user()->HasPermission('delete_cities');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    const Languages = [
        'en' => 'English',
        'fa' => 'Farsi',
    ];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function GetLanguage($translate = true): string
    {
        $lang = self::Languages[$this->lang];
        return $translate ? translate($lang) : $lang;
    }
}

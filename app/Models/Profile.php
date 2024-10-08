<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function GetLang(): string
    {
        return Translation::Languages[$this->language];
    }

    public function Translate(): void
    {
        $this->language = translate($this->GetLang());
        TranslateAll($this, ['first_name', 'last_name', 'address']);
    }
}

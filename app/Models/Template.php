<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function Items(): HasMany
    {
        return $this->hasMany(TemplateItem::class, 'template_id', 'id');
    }

    public function GetTotal(): float|int
    {
        return $this->columns * $this->rows;
    }
}

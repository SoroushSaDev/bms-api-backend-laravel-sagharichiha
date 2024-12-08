<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}

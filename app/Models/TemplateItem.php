<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TemplateItem extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function Template(): BelongsTo
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function GetRegisters()
    {
        $registers = json_decode($this->registers);
        return Register::findMany($registers);
    }

    public function GetDevice()
    {
        return $this->GetRegisters()->first()->Device;
    }
}

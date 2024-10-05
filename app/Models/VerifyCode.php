<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifyCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static function InvokeCodes($userId, $type): void
    {
        $codes = VerifyCode::where('user_id', $userId)->where('type', $type)->whereNull('invoked_at')->get();
        foreach ($codes as $code) {
            $code->invoked_at = Carbon::now();
            $code->save();
        }
    }
}

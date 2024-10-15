<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Http;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public static function boot(): void
    {
        parent::boot();
        static::creating(function (Device $device) {
            $device->user_id = auth()->check() ? auth()->id() : null;
        });
        static::updating(function (Device $device) {
            $device->user_id = auth()->check() ? auth()->id() : null;
        });
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function Registers(): HasMany
    {
        return $this->hasMany(Register::class, 'device_id', 'id');
    }

    public function Children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function Parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function SendToClient(): void
    {
        $response = Http::post(env('API_URL') . 'devices', [
            'name' => $this->name,
            'topic' => $this->mqtt_topic,
            'token' => env('API_TOKEN'),
            'description' => $this->description,
        ]);
        $data = $response->json()['data'];
        $this->server_id = $data['id'];
        $this->save();
//        foreach ($this->Registers as $register) {
//            Http::post(env('API_URL') . 'registers', [
//                'key' => $register->key,
//                'type' => $register->type,
//                'device_id' => $data['id'],
//                'title' => $register->title,
//                'value' => $register->value,
//                'token' => env('API_TOKEN'),
//            ]);
//        }
    }

    public function UpdateRegisters(): void
    {
        $response = Http::get(env('API_URL') . 'registers', [
            'device_id' => $this->server_id,
            'token' => env('API_TOKEN'),
        ]);
        $registers = $response->json()['data'];
        foreach ($registers as $register) {
            Register::where('device_id', $this->id)->where('key', $register['key'])->first()->update([
                'value' => $register['value'],
            ]);
        }
    }

    public function Translate(): void
    {
        TranslateAll($this, ['name', 'type', 'brand', 'model', 'description']);
        $this->Registers->map(function ($register) {
            $register->Translate();
        });
    }
}

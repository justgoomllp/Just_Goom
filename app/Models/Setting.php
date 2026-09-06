<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $row = static::query()->where('key', $key)->first();
        if (! $row || $row->value === null) {
            return $default;
        }

        $decoded = json_decode($row->value, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : $row->value;
    }

    public static function setValue(string $key, mixed $value): void
    {
        $stored = is_array($value) || is_bool($value) || is_int($value)
            ? json_encode($value)
            : (string) $value;

        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $stored]
        );
    }
}

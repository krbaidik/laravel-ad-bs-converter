<?php
// src/Casts/NepaliDateCast.php
namespace Krbaidik\AdBsConverter\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Krbaidik\AdBsConverter\Services\NepaliDateConverter;

class NepaliDateCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if (empty($value)) return null;
        
        return app(NepaliDateConverter::class)->parse(
            $value,
            config('nepali-date.db_store_english_digits', false),
            config('nepali-date.default_locale', 'np')
        );
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value instanceof \Krbaidik\AdBsConverter\Support\NepaliDate) {
            return (string)$value;
        }

        if (is_string($value)) {
            return $value;
        }

        return null;
    }
}
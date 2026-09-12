<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use ArrayObject;

class Translatable implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): TranslatableContent
    {
        $data = json_decode($value, true);

        // Robustness: If decode returns a string (legacy/single value), wrap it in 'en'
        if (!is_array($data)) {
            $data = ['en' => $data ?: $value];
        }
        
        return new TranslatableContent($data);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}

class TranslatableContent extends ArrayObject implements \Stringable, \JsonSerializable
{
    public function __toString(): string
    {
        $locale = app()->getLocale();
        // Fallback to 'en' if current locale is empty
        return $this[$locale] ?? $this['en'] ?? '';
    }

    public function jsonSerialize(): mixed
    {
        return $this->getArrayCopy();
    }
    
    // Add magic get to allow access like properties if needed, 
    // but array access is standard.
}

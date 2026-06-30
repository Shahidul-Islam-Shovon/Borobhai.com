<?php

namespace App\Models\Concerns;

use Vinkla\Hashids\Facades\Hashids;

trait HasHashid
{
    /** route এ id এর বদলে hashid */
    public function getRouteKeyName(): string
    {
        return 'hashid';
    }

    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    /** blade/JS এ {{ $model->hashid }} */
    public function getHashidAttribute(): string
    {
        return Hashids::encode($this->getKey());
    }

    /** URL এর hashid → model (route model binding) */
    public function resolveRouteBinding($value, $field = null)
    {
        $id = static::decodeHashid($value);
        if ($id === null) {
            abort(404);
        }
        return $this->where($this->getKeyName(), $id)->firstOrFail();
    }

    /** hashid string → raw id */
    public static function decodeHashid($value): ?int
    {
        $decoded = Hashids::decode($value);
        if (! empty($decoded)) {
            return (int) $decoded[0];
        }
        // ⚠️ migration fallback — সব module hashid পেলে শেষ ধাপে এই লাইন মুছে দাও
        return ctype_digit((string) $value) ? (int) $value : null;
    }
}
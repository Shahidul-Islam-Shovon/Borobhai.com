<?php

namespace App\Traits;

use Vinkla\Hashids\Facades\Hashids;

trait Hashidable
{
    // এটি Blade ফাইলে route() কল করার সময় ID এর বদলে Hash জেনারেট করবে
    public function getRouteKey()
    {
        return Hashids::encode($this->getKey());
    }

    // এটি URL থেকে Hash রিসিভ করে আবার ID-তে ডিকোড করে কন্ট্রোলারে পাঠাবে
    public function resolveRouteBinding($value, $field = null)
    {
        $decoded = Hashids::decode($value);
        
        // যদি ডিকোড করা না যায় (ভুল হ্যাশ), তবে 404 পেজ দেখাবে
        if (empty($decoded)) {
            abort(404);
        }

        return $this->where($field ?? $this->getRouteKeyName(), $decoded[0])->firstOrFail();
    }
}
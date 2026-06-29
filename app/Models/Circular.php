<?php

namespace App\Models;
use App\Traits\Hashidable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Circular extends Model
{
    use HasFactory;
    use Hashidable;

    protected $fillable = ['user_id', 'title', 'company', 'deadline', 'description'];

    // সার্কুলারটি কোন এলামনাই পোস্ট করেছেন তার রিলেশনশিপ
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
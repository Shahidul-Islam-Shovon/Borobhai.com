<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Hashidable;

class Report extends Model
{
    use Hashidable;
    
    protected $fillable = [
        'reporter_id', 'reportable_type', 'reportable_id',
        'reason', 'details', 'status', 'reviewed_by', 'reviewed_at',
    ];

    public function reporter() { return $this->belongsTo(User::class, 'reporter_id'); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
}
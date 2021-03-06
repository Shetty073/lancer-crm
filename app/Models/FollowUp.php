<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table = 'follow_ups';

    protected $fillable = [
        'date_time',
        'remark',
        'outcome',
    ];

    protected $casts = [
        'date_time' => 'datetime'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class, 'enquiry_id');
    }
}

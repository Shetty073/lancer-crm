<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class EnquiryStatus extends Model
{
    use SoftDeletes;

    protected $table = 'enquiry_statuses';

    protected $fillable = [
        'status',
    ];

    public function enquiries()
    {
        return $this->hasMany(Enquiry::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class DigitalDetail extends Model
{
    use SoftDeletes;

    protected $table = 'digital_details';

    protected $fillable = [
        'ref_id',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

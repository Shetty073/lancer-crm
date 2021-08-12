<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ChequeStatus extends Model
{
    use SoftDeletes;

    protected $table = 'cheque_statuses';

    protected $fillable = [
        'status',
    ];

    public function chequedetails()
    {
        return $this->hasMany(ChequeDetails::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

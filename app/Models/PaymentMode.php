<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PaymentMode extends Model
{
    use SoftDeletes;

    protected $table = 'payment_modes';

    protected $fillable = [
        'name',
        'is_cash',
        'is_cheque',
        'is_digital',
    ];

    protected $casts = [
        'is_cash' => 'boolean',
        'is_cheque' => 'boolean',
        'is_digital' => 'boolean',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'contact_no',
        'subject',
        'carpet_area',
        'agreement_value',
        'booking_amount',
        'rating',
        'remark',
        'is_active',
    ];

    protected $casts = [
        'carpet_area' => 'double',
        'agreement_value' => 'double',
        'booking_amount' => 'double',
        'is_active' => 'bool',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function configuration()
    {
        return $this->belongsTo(Configuration::class, 'configuration_id');
    }

    public function payment_mode()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_mode_id');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

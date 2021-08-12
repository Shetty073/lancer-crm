<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use SoftDeletes;

    protected $table = 'enquiries';

    protected $fillable = [
        'name',
        'business_name',
        'email',
        'contact_no',
        'subject',
        'is_lost',
        'lost_remark',
    ];

    protected $casts = [
        'is_lost' => 'boolean'
    ];

    public function follow_ups()
    {
        return $this->hasMany(FollowUp::class);
    }

    public function enquiry_status()
    {
        return $this->belongsTo(EnquiryStatus::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function configuration()
    {
        return $this->belongsTo(Configuration::class, 'configuration_id');
    }

    public function budget_range()
    {
        return $this->belongsTo(BudgetRange::class, 'budget_range_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

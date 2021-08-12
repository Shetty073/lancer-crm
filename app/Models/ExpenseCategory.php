<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use SoftDeletes;

    protected $table = 'expense_categories';

    protected $fillable = [
        'name',
        'remark',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_category_id');
    }

    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}

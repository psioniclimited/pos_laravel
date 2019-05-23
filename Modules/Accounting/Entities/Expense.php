<?php

namespace Modules\Accounting\Entities;

use App\Filters\ExpenseFilter;
use Carbon\Carbon;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use BelongsToTenants;
    protected $fillable = ['date', 'description', 'amount', 'paid_with_id'];
    public function scopeFilter($query, ExpenseFilter $filters)
    {
        return $filters->apply($query);
    }

    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

    public function paid_with()
    {
        return $this->belongsTo('Modules\Accounting\Entities\ChartOfAccount');
    }

    public function expense_details()
    {
        return $this->hasMany('Modules\Accounting\Entities\ExpenseDetail');
    }
}

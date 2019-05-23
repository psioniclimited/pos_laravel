<?php

namespace Modules\Accounting\Entities;

use App\Filters\ExpenseDetailFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class ExpenseDetail extends Model
{
    use BelongsToTenants;
    protected $fillable = ['amount', 'expense_id', 'chart_of_account_id'];
    public function scopeFilter($query, ExpenseDetailFilter $filters)
    {
        return $filters->apply($query);
    }

    public function expense()
    {
        return $this->belongsTo('Modules\Accounting\Entities\Expense');
    }

    public function chart_of_account()
    {
        return $this->belongsTo('Modules\Accounting\Entities\ChartOfAccount');
    }
}

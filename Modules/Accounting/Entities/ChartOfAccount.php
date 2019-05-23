<?php

namespace Modules\Accounting\Entities;

use App\Filters\ChartOfAccountFilter;
use HipsterJazzbo\Landlord\BelongsToTenants;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use BelongsToTenants;
    protected $fillable = ['code', 'name', 'description', 'is_payment_account', 'starting_balance', 'parent_account_id'];
    public function scopeFilter($query, ChartOfAccountFilter $filters)
    {
        return $filters->apply($query);
    }

    public function expenses()
    {
        return $this->hasMany('Modules\Accounting\Entities\Expense');
    }
}

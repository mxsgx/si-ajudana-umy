<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FinancialSubmission extends Pivot
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'financial_id', 'submission_id', 'amount',
    ];
}

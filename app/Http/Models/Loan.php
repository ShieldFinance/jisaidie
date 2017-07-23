<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'loans';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    
    protected  $attributes = [
        'daily_interest'=>0,
        'fees'=>0,
        'total'=>0,
        'transaction_ref'=>'',
        'paid'=>0,
        'invoiced'=>'0',
        'deleted'=>0
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['customer_id', 'amount_requested', 'amount_processed', 'daily_interest', 'fees', 'total', 'transaction_ref', 'paid', 'invoiced', 'status', 'net_salary', 'date_disbursed', 'deleted'];
    public function customer()
    {
        return $this->belongsTo('App\Http\Models\Customer');
    }
    
}

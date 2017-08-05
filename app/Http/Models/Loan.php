<?php

namespace App\Http\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Customer;
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
        'deleted'=>0,
        'transaction_fee'=>0,
        'last_fees_update'=>'1970-01-01 00:00:00',
        'last_sent'=>'1970-01-01 00:00:00'
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

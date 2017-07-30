<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['surname', 'crb_data','id_verified','last_name', 'other_name', 'mobile_number', 'employee_number', 'id_number', 'net_salary', 'email', 'is_checkoff', 'status', 'activation_code', 'organization_id','pin_hash'];

    public function organization()
    {
	return $this->belongsTo('App\Http\Models\Organization');
    }
    public function getCustomerByKey($key, $value)
    {
        $customer = self::where($key,$value)->first();
        return $customer;
    }
    public function loans()
    {
        return $this->hasMany('App\Http\Models\Loan');
    }
    
    public function getLoanStatement($params){
        //$loans = Loan::
    }
    
    public function devices()
    {
        return $this->hasMany('App\Http\Models\CustomerDevice');
    }
   
	
}

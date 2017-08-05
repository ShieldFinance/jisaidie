<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Ussd extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ussds';

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
    protected $fillable = ['name','sessionId','serviceCode','pin_verified','is_pin_change','level','action','no_net_salary','is_new','is_terms','is_statement','client_name','net_salary','advance_amount','company','manager','manager_mobile','employee_count','phoneNumber','text'];

    
   
}

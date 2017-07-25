<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerDevice extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'customer_devices';

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
    protected $fillable = ['device_id', 'customer_id'];

    public function customer()
    {
	return $this->belongsTo('App\Http\Models\Customer');
    }
}

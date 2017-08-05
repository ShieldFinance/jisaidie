<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCommand extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'service_commands';

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
    protected $fillable = ['processing_function', 'service_id', 'level', 'description'];

    public function service()
	{
		return $this->belongsTo('App\Http\Models\Service');
	}
	
}

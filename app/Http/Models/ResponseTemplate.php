<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseTemplate extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'response_templates';

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
    protected $fillable = ['name', 'subject', 'message', 'type', 'service_id', 'description', 'status'];

    
}

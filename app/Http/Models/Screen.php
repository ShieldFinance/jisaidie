<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Screen extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'screens';

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
    protected $fillable = ['title', 'message', 'icon', 'order', 'status'];

    
}

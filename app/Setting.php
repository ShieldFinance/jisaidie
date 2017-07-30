<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

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
    protected $fillable = ['setting_name', 'setting_value', 'setting_description', 'created_at', 'updated_at'];

    public static function getSettings($key){
        return self::where('setting_name',$key)->first()->setting_value;
    }
}

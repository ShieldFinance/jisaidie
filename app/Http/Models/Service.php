<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'services';

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
    protected $fillable = ['name', 'product', 'description'];
    
    /**
     * Get the servicecommands
     */
    public function service_commands()
    {
        return $this->hasMany('App\Http\Models\ServiceCommand');
    }
    
    public function getServiceCommandsByServiceId($service_id){
         $serviceCommands = App\Http\Models\Service::find($service_id)->service_commands()
               ->orderBy('level', 'asc')
               ->get();
         return $serviceCommands;
     }
     public function getServiceCommandsByServiceName($service_name){
         $serviceCommands = \App\Http\Models\Service::where('name',$service_name)
               ->first()->service_commands()
                 ->get();
         return $serviceCommands;
     }
     public function getServiceByName($serviceName){
         $service = App\Http\Models\ServiceCommand::where('name', $serviceName)
               ->get();
         return $serviceCommands;
     }

    
}

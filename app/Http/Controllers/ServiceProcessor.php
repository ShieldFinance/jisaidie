<?php

namespace App\Http\Controllers;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use App\Http\Models\Service;
use App\Http\Models\Transaction;
class ServiceProcessor extends Controller
{
    public function __construct(){
        $this->app = App::getFacadeRoot();
    }
    public function doProcess($request){
        $this->payload = [];
        $response = array();
        $serviceModel = Service::where('name',$request->input('action'))->first();
        if($serviceModel){
            $service =  $this->app->make($serviceModel->product);
            $serviceCommands = $serviceModel->getServiceCommandsByServiceName($request->input('action'));
            $payload = json_decode($request['request'], true);
            
            $payload['transaction_id']=$this->logTransaction($serviceModel, $request);
            foreach($serviceCommands as $command){
                if (method_exists($service, $command->processing_function))
                {
                    $processing_function=$command->processing_function;
                    $payload=$service->$processing_function($payload);
                    $response[$command->processing_function]=$payload;
                }
                else
                {
                   echo 'no method, '.$command->processing_function;exit;
                }
            }
        }
        return $response;
    }
    
    public function logTransaction($service, $request){
        $reqst = array();
        $reqst['action'] = $request->input('action');
        $reqst['request'] = json_decode($request['request'], true);
        $transaction = new \App\Http\Models\Transaction(['service_id'=>$service->id,'request'=>json_encode($reqst),'status'=>'pending']);
        $transaction->save();
        return $transaction->id;  
    }
    
    public function updateTransaction($payload){
        $transaction = Transaction::find($payload['transaction_id']);
        foreach($payload as $key=>$value){
            if(Schema::hasColumn($transaction->getTable(), $key))
            {
                $transaction->$key = $value;
            }
        }
        $transaction->save();
    }
}

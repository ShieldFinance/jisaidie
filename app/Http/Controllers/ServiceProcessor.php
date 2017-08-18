<?php

namespace App\Http\Controllers;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use App\Http\Models\Service;
use App\Http\Models\Transaction;
use App\Setting;
use App\Http\Controllers\Services\ResponseTemplatesController;
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
            $responseProcessor = new ResponseTemplatesController();
            $settings = new Setting();
            $service =  $this->app->make($serviceModel->product, [$settings, $responseProcessor]);
            $serviceCommands = $serviceModel->getServiceCommandsByServiceName($request->input('action'));
           
            $payload = json_decode($request['request'], true);
            $payload['transaction_id']=$transaction_id=$this->logTransaction($serviceModel, $request);
            $payload['service_id'] = $serviceModel->id;
            //first command will always execute
            $canProcessNext = true;
            foreach($serviceCommands as $command){
                if (method_exists($service, $command->processing_function))
                {
                    $processing_function=$command->processing_function;
                    if($canProcessNext){
                        $payload=$service->$processing_function($payload);
                        $response[$command->processing_function]=$payload;
                    }
                    if(isset($payload['command_status']) && $payload['command_status']==config('app.responseCodes')['command_successful']){
                        $canProcessNext = true;
                    }else{
                        $canProcessNext = false;
                        break;
                    }
                }
                else
                {
                   echo 'no method, '.$command->processing_function;exit;
                }
            }
        }
        $this->updateTransaction($response, $transaction_id);
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
    
    public function updateTransaction($payload, $transaction_id){
        $transaction = Transaction::find($transaction_id);
        $transaction->response = json_encode($payload);
        $transaction->save();
    }
}

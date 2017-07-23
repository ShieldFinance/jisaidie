<?php
namespace App\Services;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Illuminate\Support\Facades\Schema;
use App\Transformers\CustomerTransformer;
use Chrisbjr\ApiGuard\Http\Controllers\ApiGuardController;
use App\Http\Models\Customer;
class CustomerService extends ApiGuardController{
    
    public function  create_customer_profile($payload){
        try{
        
        if(isset($payload['mobile_number']) && isset($payload['device_id'])){
            //first check if customer exists
            $customer =  Customer::where('mobile_number', $payload['mobile_number'])->first();
            $device = \App\Http\Models\CustomerDevice::where("device_id", $payload['device_id'])->get();
            if(count($customer)==0 && count($device)==0){
                $attributes = ['mobile_number'=>$payload['mobile_number'],'id_number'=>'','first_name'=>'','last_name'=>'','surname'=>'','email'=>'','status'=>'new'];
                $newCustomer = new Customer($attributes);
                $newCustomer->status = config('app.customerStatus')['new'];
                $newCustomer->save();
                $payload['customer'] =  $this->response->withItem($newCustomer, new CustomerTransformer());
                $payload['response_status'] =  config('app.responseCodes')['new_device_new_msisdn'];
                $payload['response_string'] = "User created successfully";
                $payload['send_notification'] = true;
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }elseif(count($customer) && count($device)==0 and $payload['device_id'] && isset($customer->id_number) && strlen($customer->id_number)){
                //add new device and deactivate old account
                $newDevice = new \App\Http\Models\CustomerDevice(['device_id'=>$payload['device_id'],'customer_id_number'=>$customer->id_number]);
                $newDevice->save();
                $payload['customer'] =  $this->response->withItem($customer, new CustomerTransformer());
                $payload['response_status'] =config('app.responseCodes')['existing_msisdn_new_device'];
                $payload['response_string'] = "User device added successfully";
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }elseif($customer and count($device)){
                $payload['send_notification'] = true;
                $payload['customer'] =  $this->response->withItem($customer, new CustomerTransformer());
                $payload['response_status'] =config('app.responseCodes')['existing_msisdn_existing_device'];
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }
            elseif(count($device) and !count($customer)){
                $attributes = ['mobile_number'=>$payload['mobile_number'],'id_number'=>'','first_name'=>'','last_name'=>'','surname'=>'','email'=>'','status'=>'new'];
                $newCustomer = new Customer($attributes);
                $newCustomer->status = config('app.customerStatus')['new'];
                $newCustomer->save();
                $payload['customer'] =  $this->response->withItem($newCustomer, new CustomerTransformer());
                $payload['response_status'] =config('app.responseCodes')['existing_device_new_msisdn'];
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }
            else{
                $payload['response_status'] ='99';
                $payload['response_string'] = "Missing parameters";
                $payload['command_status'] = config('app.responseCodes')['failed'];
            }
        }else{
            $payload['response_status'] ='99';
            $payload['response_string'] = "You must provide both device id and phone number";
            $payload['command_status'] = config('app.responseCodes')['failed'];
        }
      
    }catch(Exception $ex){
        $payload['response_string'] ="Error creating user";
        $payload['response_status'] ='99';
        $payload['command_status'] = config('app.responseCodes')['failed'];
    }
    return $payload;
    }
    
    public function update_customer_profile($payload){
        try{
            $customer = Customer::where('mobile_number', $payload['mobile_number'])->first();
           
            if(count($customer)){
                
                foreach($payload as $key=>$value){
                    if(Schema::hasColumn('customers', $key))
                    {
                     $customer->$key = $value;
                    }
                }
                $customer->save();
                if(isset($payload['device_id'])){
                    $device = \App\Http\Models\CustomerDevice::where("device_id", $payload['device_id'])->first();
                    if(!$device){
                        $newDevice = new \App\Http\Models\CustomerDevice(['device_id'=>$payload['device_id'],'customer_id_number'=>$customer->id_number]);
                        $newDevice->save(); 
                    }
                }
                $payload['customer'] =  $this->response->withItem($customer, new CustomerTransformer());
                $payload['response_string'] ="Customer Details Updated";
                $payload['response_status'] ='00';
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }else{
                $payload['response_string'] ="Customer Does not exist";
                $payload['response_status'] ='96';
                $payload['command_status'] = config('app.responseCodes')['failed'];
            }
        } catch (Exception $ex) {
            $payload['response_string'] ="Error updating customer";
            $payload['command_status'] = config('app.responseCodes')['failed'];
            $payload['response_status'] ='99';
        }
        return $payload;
    }
    public function update_activation_code($payload){
        if(isset($payload['activation_code']) && isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            if($customer){
                $customer->activation_code = $payload['activation_code'];
                $customer->status = config('app.customerStatus')['activation_code'];
                $customer->save();
                $payload['response_status'] ='00';
                $payload['response_string'] ="Activation Code Added";
                $payload['customer']=$this->response->withItem($customer, new CustomerTransformer());
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }else{
                $payload['response_status'] ="99";
                $payload['response_string'] ="Customer not found";
                $payload['command_status'] = config('app.responseCodes')['failed'];
            }
        }
        return $payload;
    }
    public function activate_customer($payload){
        if(isset($payload['activation_code']) && isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            if($customer && $customer->activation_code==$payload['activation_code']){
                $customer->activation_code = '';
                $customer->status = config('app.customerStatus')['active'];
                $customer->save();
                $payload['response_status'] ='00';
                $payload['response_string'] ="Account Activated";
                $payload['customer']=$this->response->withItem($customer, new CustomerTransformer());
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }else{
                $payload['response_status'] ="99";
                $payload['response_string'] ="Invalid activation code";
                $payload['command_status'] = config('app.responseCodes')['failed'];
            }
        }
        return $payload;
    }
    public function change_customer_status($payload){
        if(isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number']);
            if($customer){
                $customer->status = $payload['customer_status'];
                $customer->save();
                $payload['response_status'] = "00";
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }
            $payload['customer']=$this->response->withItem($customer, new CustomerTransformer());
        }else{
            $payload['command_status'] = config('app.responseCodes')['failed'];
        }
        return $payload;
    }
    public function send_notification($payload){
        $response = array();
        if(isset($payload['send_notification']) && $payload['send_notification']){
            $response['response_string'] ="Notification sent";
            $response['response_status'] ='00';
        }else{
            $response['response_string'] ="Notification not sent";
            $response['response_status'] ='00';
        }
        return $response;
    }
    
}


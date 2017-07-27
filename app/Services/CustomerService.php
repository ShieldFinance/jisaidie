<?php
namespace App\Services;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Illuminate\Support\Facades\Schema;
use App\Transformers\CustomerTransformer;
use App\Transformers\MessageTransformer;
use Chrisbjr\ApiGuard\Http\Controllers\ApiGuardController;
use App\Http\Models\Customer;
use App\Http\Models\Loan;
use App\Http\Models\CustomerDevice;
use App\Setting;
use App\Http\Controllers\Services\ResponseTemplatesController;
use App\Http\Models\Message;
use Carbon\Carbon;
class CustomerService extends ApiGuardController{
   
    public function  create_customer_profile($payload){
        $responseString = '';
        $responseStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        try{
        
        if(isset($payload['mobile_number']) && isset($payload['device_id'])){
            //first check if customer exists
            $customer =  Customer::where('mobile_number', $payload['mobile_number'])->first();
            $device = \App\Http\Models\CustomerDevice::where("device_id", $payload['device_id'])->get();
            $activation_code = "";
            $payload['subject_placeholders'] = array();
            $payload['message_placeholders'] = array();
            if(isset($payload['activation_code'])){
                $activation_code = $payload['activation_code'];
                $payload['message_placeholders']['[activation_code]']=$payload['activation_code'];
            }
            if(count($customer)==0 && count($device)==0){
                $attributes = [
                    'mobile_number'=>$payload['mobile_number'],
                    'id_number'=>'',
                    'last_name'=>'',
                    'other_name'=>'',
                    'surname'=>'',
                    'email'=>'',
                    'status'=>config('app.customerStatus')['new'],
                    'activation_code'=>$activation_code];
                $newCustomer = new Customer($attributes);
                $newCustomer->status = config('app.customerStatus')['new'];
                $newCustomer->save();
                //add device
                $device = new \App\Http\Models\CustomerDevice(['device_id'=>$payload['device_id'],'customer_id'=>$newCustomer->id]);
                $device->save();
                $payload['customer'] =  $this->response->withItem($newCustomer, new CustomerTransformer());
                $responseStatus =  config('app.responseCodes')['new_device_new_msisdn'];
                $responseString = "User created successfully";
                $payload['send_notification'] = true;
                $payload['send_now']=true;
                $commandStatus = config('app.responseCodes')['command_successful'];
            }elseif(count($customer) && count($device)==0 and $payload['device_id']){
                //add new device
                $newDevice = new \App\Http\Models\CustomerDevice(['device_id'=>$payload['device_id'],'customer_id'=>$customer->id]);
                $newDevice->save();
                $payload['customer'] =  $this->response->withItem($customer, new CustomerTransformer());
                $responseStatus =config('app.responseCodes')['existing_msisdn_new_device'];
                $responseString = "User device added successfully";
                $commandStatus = config('app.responseCodes')['command_successful'];
            }elseif($customer and count($device)){
                $payload['send_notification'] = false;
                $payload['customer'] =  $this->response->withItem($customer, new CustomerTransformer());
                $responseStatus =config('app.responseCodes')['existing_msisdn_existing_device'];
                $commandStatus = config('app.responseCodes')['command_successful'];
                $responseString = "Existing msisdn existing device";
            }
            elseif(count($device) and !count($customer)){
                $attributes = [
                    'mobile_number'=>$payload['mobile_number'],
                    'id_number'=>'',
                    'last_name'=>'',
                    'other_name'=>'',
                    'surname'=>'',
                    'email'=>'',
                    'status'=>'new',
                    'activation_code'=>$activation_code];
                $newCustomer = new Customer($attributes);
                $newCustomer->status = config('app.customerStatus')['new'];
                $newCustomer->save();
                $payload['send_notification'] = true;
                $payload['customer'] =  $this->response->withItem($newCustomer, new CustomerTransformer());
                $responseStatus =config('app.responseCodes')['existing_device_new_msisdn'];
                $commandStatus = config('app.responseCodes')['command_successful'];
                $responseString = "Existing device new msisdn";
            }
            else{
                $responseStatus = config('app.responseCodes')['command_failed'];
                $responseString = "Missing parameters";
                $commandStatus = config('app.responseCodes')['command_failed'];
            }
        }else{
            $payload['response_status'] ='99';
            $responseString = "You must provide both device id and phone number";
            $payload['command_status'] = config('app.responseCodes')['command_failed'];
        }
      
    }catch(Exception $ex){
        $responseString = "Error creating user";
        $responseStatus =config('app.responseCodes')['command_failed'];
        $commandStatus = config('app.responseCodes')['command_failed'];
    }
    $payload['response_string'] = $responseString;
    $payload['response_status'] = $responseStatus;
    $payload['command_status'] = $commandStatus;
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
                        $newDevice = new \App\Http\Models\CustomerDevice(['device_id'=>$payload['device_id'],'customer_id'=>$customer->id_number]);
                        $newDevice->save(); 
                    }
                }
                $payload['customer'] =  $this->response->withItem($customer, new CustomerTransformer());
                $payload['response_string'] ="Customer Details Updated";
                $payload['response_status'] =config('app.responseCodes')['customer_profile_updated'];
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }else{
                $payload['response_string'] ="Customer Does not exist";
                $payload['response_status'] ='96';
                $payload['command_status'] = config('app.responseCodes')['command_failed'];
            }
        } catch (Exception $ex) {
            $payload['response_string'] ="Error updating customer";
            $payload['command_status'] = config('app.responseCodes')['command_failed'];
            $payload['response_status'] =config('app.responseCodes')['customer_profile_not_updated'];
        }
        return $payload;
    }
    public function update_device_token($payload){
        $response = array();
        $responseStatus = config('app.responseCodes')['command_failed'];
        if(isset($payload['registration_token']) && isset($payload['device_id']) && isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            $device = CustomerDevice::where([
                ['device_id','=',$payload['device_id']],
                ['customer_id','=',$customer->id]
            ])->update(['registration_token'=>$payload['registration_token']]);
            $response['response_status'] =  config('app.responseCodes')['command_failed'];
            $payload['command_status'] = config('app.responseCodes')['command_failed'];
        }
        return $response;
    }
    public function update_activation_code($payload){
        $responseString = '';
        $responseStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        $responseProcessor = new ResponseTemplatesController();
        $response = array();
        if(isset($payload['activation_code']) && isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            if($customer){
                $payload['subject_placeholders'] = array();
                $payload['message_placeholders'] = array();
                $customer->activation_code = $payload['activation_code'];
                $customer->status = config('app.customerStatus')['activation_code'];
                $customer->save();
                $responseStatus =config('app.responseCodes')['activation_code_updated'];
                $responseString ="Activation Code Added";
                $payload['customer']=$this->response->withItem($customer, new CustomerTransformer());
                $commandStatus = config('app.responseCodes')['command_successful'];
                $payload['send_notification'] = true;
                $payload['send_now']=true;
                $payload['message_type'] = 'inapp';
                $payload['msisdn'] = $customer->mobile_number;
                $payload['message_placeholders']['[activation_code]']=$payload['activation_code'];
                $payload['message_placeholders']['[customer_name]']=$customer->surname;
            }else{
                $responseStatus =config('app.responseCodes')['activation_code_not_updated'];
                $responseString ="Customer not found";
                $commandStatus = config('app.responseCodes')['command_failed'];
            }
        }
        $payload['command_status'] = $commandStatus;
        $payload['response_status'] = $responseStatus;
        $payload['response_string'] = $responseString;
        return $payload;
    }
    public function activate_customer($payload){
        if(isset($payload['activation_code']) && isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            if($customer && $customer->activation_code==$payload['activation_code']){
                $payload['subject_placeholders'] = array();
                $payload['message_placeholders'] = array();
                $customer->activation_code = '';
                $customer->status = config('app.customerStatus')['active'];
                $customer->save();
                $payload['response_status'] =config('app.responseCodes')['profile_activated'];
                $payload['response_string'] ="Account Activated";
                $payload['customer']=$this->response->withItem($customer, new CustomerTransformer());
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
                $payload['send_notification'] = true;
                $payload['send_now']=true;
                $payload['message_placeholders']['[customer_name]']=$customer->surname;
                $payload['email'] = $customer->email;
                $payload['msisdn'] = $customer->mobile_number;
            }else{
                $payload['response_status'] =config('app.responseCodes')['profile_not_activated'];
                $payload['response_string'] ="Invalid activation code";
                $payload['command_status'] = config('app.responseCodes')['command_failed'];
            }
        }else{
            $payload['response_status'] =config('app.responseCodes')['profile_not_activated'];
            $payload['response_string'] ="You must provide both activation code and mobile number";
            $payload['command_status'] = config('app.responseCodes')['command_failed'];
        }
        return $payload;
    }
    public function change_customer_status($payload){
        if(isset($payload['mobile_number'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            if($customer){
                $customer->status = $payload['customer_status'];
                $customer->save();
                $payload['response_status'] = "00";
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }
            $payload['customer']=$this->response->withItem($customer, new CustomerTransformer());
        }else{
            $payload['command_status'] = config('app.responseCodes')['command_failed'];
        }
        return $payload;
    }
    public function fetch_customer_statement($payload){ 
        $response = array();
        $loans = array();
        $where = [];
        $loanSummary = [];
        $loanSummary['total_disbursed'] = 0;
        $loanSummary['total_paid'] = 0;
        $loanSummary['total_balance'] = 0;
        if(isset($payload['mobile_number'])){
            $limit = 0;
            if(isset($payload['limit'])){
                $limit = (int)$payload['limit'];
            }
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            if($customer){
                $where[]=['customer_id','=',$customer->id];
                $loans = Loan::where($where);
                if(isset($payload['date_from']) && isset($payload['date_to'])){
                   $loans =$loans->whereDate('created_at','>=',$payload['date_from']);
                    $loans =$loans->whereDate('created_at','<=',$payload['date_to']);
                }
                $loans = $loans->orderBy('id','desc');
                if($limit > 0){
                    $loans = $loans->limit($limit);
                }
                $loans = $loans->get();
                if(count($loans)){
                    foreach($loans as $loan){
                        $loanSummary['total_paid']+=$loan->paid;
                        $dateDisbursed = new Carbon($loan->date_disbursed);
                        $loan->sent_at = $dateDisbursed->format('F d, Y');
                        $expiryDate = $dateDisbursed->copy()->addDays(60);
                        $loan->days_left = $dateDisbursed->diff($expiryDate)->days;
                        $loan->expiry = $expiryDate->format('F d, Y');
                        $loan->balance = $loan->total-$loan->paid;
                        $loan->state = $loan->status;
                        if($loan->status==config('app.loanStatus')['disbursed'] || $loan->status==config('app.loanStatus')['locked']|| $loan->status==config('app.loanStatus')['paid']){
                             $loanSummary['total_disbursed']+=$loan->amount_processed;
                        }
                        $loan->amount_requested=number_format($loan->amount_requested,0,'.',',');
                        $loan->total=number_format($loan->total,0,'.',',');
                    }
                    
                    $loanSummary['total_balance']=$loanSummary['total_disbursed']-$loanSummary['total_paid'];
                }
                
            }
        }
        $loanSummary['total_disbursed'] = number_format($loanSummary['total_disbursed'],0,'.',',');
        $loanSummary['total_paid']  = number_format($loanSummary['total_paid'],0,'.',',');
        $loanSummary['total_balance'] = number_format($loanSummary['total_balance'],0,'.',',');
        $response['summary']=$loanSummary;
        $response['loans']=$loans;
        return $response;
    }
    public function send_notification($payload){
        $responseString = '';
        $responseStatus = '';
        $commandStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        $responseProcessor = new ResponseTemplatesController();
        $response = array();
        $response['mobile_number']=$payload['mobile_number'];
        if(isset($payload['send_notification']) && $payload['send_notification']){
           $payload['msisdn'] = $payload['mobile_number'];
           if($responseProcessor->processResponse($payload)){
               $responseStatus = config('app.responseCodes')['command_successful'];
               $commandStatus = config('app.responseCodes')['command_successful'];
               $responseString="Notification sent";
           }else{
               $responseStatus = config('app.responseCodes')['command_failed'];
               $commandStatus = config('app.responseCodes')['command_failed'];
               $responseString="Notification not sent";
           }
        }
        $response['response_string'] = $responseString;
        $response['response_status'] = $responseStatus;
        $response['command_status'] = $commandStatus;
        return $response;
    }
    
    public function fetch_messages($payload)
    {
        $response = array();
        $responseString = '0k';
        $responseStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        if(isset($payload['mobile_number'])){
            $limit = 1000;
            if(isset($payload['limit'])){
                $limit = (int)$payload['limit'];
            }
            $where = array();
            $where[]=array('recipient','=',$payload['mobile_number']);
            if(isset($payload['type'])){
                $where[]=array('type','=',$payload['type']);
            }
            
            $messages = Message::where($where)->orderBy("id","desc")->limit($limit)->get();
            if(count($messages)){
                foreach($messages as $message){
                    $message->sent_at = $message->updated_at->format('F d, Y');
                }
                $response['messages'] = $messages;//$this->response->withCollection($messages, new MessageTransformer());
            $responseString = '0k';
            $responseStatus =  config('app.responseCodes')['command_successful'];
            $commandStatus =  config('app.responseCodes')['command_successful'];
 
            }else{
                $responseString = 'No messages';
                $responseStatus =  config('app.responseCodes')['command_successful'];
                $commandStatus =  config('app.responseCodes')['command_successful'];
            }
        }
        $response['response_status'] = $responseStatus;
        $response['response_string'] = $responseString;
        $response['command_status'] = $commandStatus;
        return $response;
    }
    
    public function check_customer_status($payload){
        $app = \App::getFacadeRoot();
        $loanService = $app->make('Loan');
        $response = array();
        $responseString = '';
        $responseStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        if(isset($payload['mobile_number'])){
            $customer = new Customer();
            $customer = $customer->getCustomerByKey('mobile_number',$payload['mobile_number']);
            if($customer){
                $canBorrow = $loanService->customerCanBorrow($customer);
                $response['can_borrow'] = $canBorrow;
                $response['profile_status'] = $customer->status;
                $response['response_status']=config('app.responseCodes')['command_successful'];
            }else{
                $response['response_status']=config('app.responseCodes')['customer_does_not_exist'];
                $response['command_status'] = config('app.responseCodes')['command_failed'];
            }
        }
        $response['response_string'] = $responseString;
        $response['response_status'] = $responseStatus;
        $response['command_status'] = $commandStatus;
        return $response;
    }
    
    public function pin_reset_notify($payload){
        $app = \App::getFacadeRoot();
        $messageService = $app->make('Loan');
        $response = array();
        $responseString = '';
        $responseStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        $customer = new Customer();
        $customer = $customer->getCustomerByKey('mobile_number',$payload['mobile_number']);
        if($customer){
            $device = CustomerDevice::where('customer_id',$customer->id)->orderBy('id','desc');
            $response['send_notification'] = true;
            $response['send_now'] = true;
            $response['mobile_number']=$payload['mobile_number'];
            $response['service_id']=$payload['service_id'];
            $responseString = "Pin reset ok";
            $responseStatus = config('app.responseCodes')['command_successful'];
            $commandStatus = config('app.responseCodes')['command_successful'];
        }else{
             $responseStatus = config('app.responseCodes')['customer_does_not_exist'];
        }
        $response['response_string'] = $responseString;
        $response['response_status'] = $responseStatus;
        $response['command_status'] = $commandStatus;
        return $response;
    }
}


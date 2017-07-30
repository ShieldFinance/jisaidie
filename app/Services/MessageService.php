<?php

namespace App\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use App\Helpers\AfricasTalkingGateway;
use App\Http\Models\Message;
use App\Setting;
use App\Mail\AppEmail;
use Edujugon\PushNotification\PushNotification;
use App\Http\Models\CustomerDevice;
use App\Http\Models\Customer;
class MessageService {
    public $messageTypes = array('sms','email','inapp');
    public function sendMessages() {
        $messages = Message::where([
                    ['status', '=', 'pending'],
                ])->limit(1)->get();
        if (count($messages)) {
            foreach ($messages as $message) {
                $type = strtoupper($message->type);
                $messageFunction = 'send' . $type;
                $payload = array();
                $payload['recepient'] = $message->recipient;
                $payload['message'] = $message->message;
                $payload['subject'] = $message->subject;
                $data = $this->$messageFunction($payload);
                $message->status = $data['status'];
                $message->attempts += $data['attempts'];
                $message->save();
            }
        }
    }
    /**
     * Send single message
     * @param type $payload
     */
    public function sendMessage($payload){
         
        $sent = false;
        if(isset($payload['message_id'])){
            $message = Message::find($payload['message_id']);
            if($message){
                $messageFunction = 'send' . $message->type;
                $details = array();
                $details['recepient'] = $message->recipient;
                $details['message'] = $message->message;
                $details['subject'] = $message->subject;
                $data = $this->$messageFunction($details);
                $message->status = $data['status'];
                $message->attempts += $data['attempts'];
                if($data['status']=='Success'){
                    $sent = true;
                }
                $message->save();
            }
            
        }
        return $sent;
    }
   
    public function sendSMS($payload) {
        $username = Setting::where('setting_name', 'prsp_username')->first()->setting_value;
        $key = Setting::where('setting_name', 'prsp_api_key')->first()->setting_value;
        $senderId = Setting::where('setting_name', 'prsp_sender_id')->first()->setting_value;
        $gateway = new AfricasTalkingGateway($username, $key);
        $sent = $gateway->sendMessage($payload['recepient'], $payload['message'], $senderId);

        if ($sent[0]->status == "Success") {
            $data = array(
                'status' => "Success",
                'attempts' => 1,
                'state' => 1,
                'cost' => preg_replace("/[^0-9,.]/", "", $sent[0]->cost)
            );
        } else if ($sent[0]->status == "Sent") {
            $data = array(
                'status' => "Sent",
                'attempts' => 1,
                'state' => 1,
                'cost' => preg_replace("/[^0-9,.]/", "", $sent[0]->cost)
            );
        } else if (trim($sent[0]->status) == "User In BlackList") {
            $data = array(
                'status' => "User In BlackList",
                'attempts' => 1,
                'state' => o,
                'cost' => preg_replace("/[^0-9,.]/", "", $sent[0]->cost)
            );
        } else {
            $data = array(
                'status' => "Failed",
                'attempts' => 1,
                'state' => 0,
            );
        }

        return $data;
    }

    public function sendEMAIL($payload) {
       \Mail::to($payload['recepient'])->send(new AppEmail($payload['message'],$payload['subject']));
       return array('status'=>1,'attempts'=>1);
    }
    
    public function sendINAPP($payload){
        $data = $this->sendANDROID($payload);
        //$this->sendIOS($payload); Not implemented
        return $data;
    }
    
    public function sendANDROID($payload){
        $data = array();
        $push = new PushNotification('fcm');
        $customer = Customer::where('mobile_number',$payload['recepient'])->first();
        $device = CustomerDevice::where('customer_id',$customer->id)
        ->orderBy('id','desc')
        ->first();
      
        $deviceTokens =array($device->registration_token);
        $push->setMessage([
            'notification' => [
                    'title'=>$payload['subject'],
                    'body'=>$payload['message'],
                    'sound' => 'default'
                    ]
            ])
            ->setDevicesToken($deviceTokens);
        $response = $push->send()->getFeedback();
        var_dump($response);exit;
        $data['status'] = $response->success?'Success':'pending';
        if($response->failure){
            $data['status']='failed';
        }
        $data['attempts']=1;
        return $data;
    }

}

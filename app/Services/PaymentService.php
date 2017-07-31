<?php

namespace App\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use App\Helpers\AfricasTalkingGateway;
use App\Helpers\AfricasTalkingGatewayException;
use App\Http\Models\Message;
use App\Setting;
use App\Mail\AppEmail;
use Edujugon\PushNotification\PushNotification;
use App\Http\Models\CustomerDevice;
use App\Http\Models\Customer;
use App\Http\Models\Loan;
class PaymentService {

    /**
     * Send money to a single customer
     * @param type $payload
     */
    public function sendMoney($payload){
        $data = array();
        if(isset($payload['amount']) && isset($payload['mobile_number'])){
           $gatewayFunction = 'send' . strtoupper($payload['gateway']);
            $details = array();
            $details['recepient'] = $payload['mobile_number'];
            $details['amount'] = 50;
            $data = $this->$gatewayFunction($details);
            
        }
        return $data;
    }
   
    public function sendMPESA($payload) {
        $username = Setting::where('setting_name', 'prsp_username')->first()->setting_value;
        $key = Setting::where('setting_name', 'prsp_api_key')->first()->setting_value;
        $senderId = Setting::where('setting_name', 'prsp_sender_id')->first()->setting_value;
        $productName = Setting::where('setting_name', 'mpesa_product_name')->first()->setting_value;
        $gateway = new AfricasTalkingGateway($username, $key);
        $recipient1   = array("phoneNumber" => "+".$payload['recepient'],
                       "currencyCode" => "KES",
                       "amount"       => $payload['amount'],
                       "metadata"     => array("name"   => "Test Api",
                                               "reason" => "Develop")
              );
        $recipients  = array($recipient1);
        $responses = $gateway->mobilePaymentB2CRequest($productName, $recipients);
        $response = $responses[0];
        if ($response->status == "Queued") {
        $return['transaction_id']=$response->transactionId;
        $return['raw_response']=$response->provider;
      } 
        return $responses;
    }
    
    public function initiate_checkout($payload){
        $username = Setting::where('setting_name', 'prsp_username')->first()->setting_value;
        $key = Setting::where('setting_name', 'prsp_api_key')->first()->setting_value;
        $senderId = Setting::where('setting_name', 'prsp_sender_id')->first()->setting_value;
        $productName = Setting::where('setting_name', 'mpesa_product_name')->first()->setting_value;
        $gateway = new AfricasTalkingGateway($username, $key);
        $currencyCode = 'KES';
        $phoneNumber = $payload['mobile_number'];
        $customer = Customer::where('mobile_number',$phoneNumber)->first();
        $loan = $customer->loans->take(1);
        $loan = $loan[0];
        $amount = $loan->total - $loan->paid;
        $metadata = isset($payload['metadata'])?$payload['metadata']:array('mobile_number'=>$phoneNumber);
        $response = array();
        $response['transactionId'] = 0;
        try{
         $response['transactionId'] = $gateway->initiateMobilePaymentCheckout($productName, $phoneNumber,$currencyCode,$amount,$metadata);
        }catch(AfricasTalkingGatewayException $ex){
             $response['message']=$ex->getMessage();
        }
        return $response;
    }
  

}

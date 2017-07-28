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
class PaymentService {
   
    
    public function sendMoney($payload) {
        
       $productName="";
       $recipients=array();
       $recipients[]= array("phoneNumber" => "+254711XXXYYY",
                       "currencyCode" => "KES",
                       "amount"       => 10.50,
                       "metadata"     => array("name"   => "Clerk",
                                               "reason" => "May Salary")
                        );
       $responses = $gateway->mobilePaymentB2CRequest($productName, $recipients);
       return $responses;
    }
    

}

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

class MessageService {

    public function sendMessages() {
        $messages = Message::where([
                    ['status', '=', 'pending'],
                    ['type', '=', 'email']
                ])->limit(2)->get();
        if (count($messages)) {
            foreach ($messages as $message) {
                $type = strtoupper($message->type);
                $messageFunction = 'send' . $type;
                $payload = array();
                $payload['recepient'] = $message->recipient;
                $payload['message'] = $message->message;
                $data = $this->$messageFunction($payload);
                $message->status = $data['status'];
                $message->attempts += $data['attempts'];
                $message->save();
            }
        }
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

    }

}

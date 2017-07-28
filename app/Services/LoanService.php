<?php
namespace App\Services;
use Carbon\Carbon;
use App\Http\Models\Loan;
use App\Http\Models\Customer;
use App\Http\Models\Payment;
use App\Http\Models\Service;
use App\Setting;
use App\Http\Controllers\Services\ResponseTemplatesController;
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class LoanService{
    public function __construct(Setting $setting, ResponseTemplatesController $responseProcessor){
        $this->setting = $setting;
        $this->responseProcessor = $responseProcessor;
    }
    /**
     * Create a new loan if a cutomer qualifies
     * @param type $payload
     * @return payload
     */
    public function  create_loan($payload){
        $maximumLoan = floatval($this->setting->where('setting_name','maximum_loan')->first()->setting_value);
        $minimumAmount = floatval($this->setting->where('setting_name','minimum_loan')->first()->setting_value);
        
        $responseString='';
        if($payload['amount'] < $minimumAmount){
            $responseString.='The amount applied is less than allowed minimum of '.$minimumAmount."|";
        }
        if($payload['amount'] > $maximumLoan){
            $responseString.='The amount applied is greater than allowed maximum of '.$minimumAmount;
        }
        if(strlen($responseString)==0){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            $loanStatus = $this->customerCanBorrow($customer);
            if($loanStatus['can_borrow']){
                if(isset($payload['amount']) && $payload['amount'] >= $minimumAmount && $payload['amount'] <=$maximumLoan){
                    $loan = new Loan();
                    $loan->amount_requested = $payload['amount'];
                    $loan->customer_id = $customer->id;
                    $loan->status = config('app.responseCodes')['loan_pending'];
                    $loan->save();
                    $payload['response_string'] = 'Loan Created';
                    $payload['response_status'] = config('app.responseCodes')['command_successful'];
                    $payload['command_status'] = config('app.responseCodes')['command_successful'];
                    $payload['send_notification'] = true;
                    $payload['email'] = $this->setting->where('setting_name','new_loan_application_recipients')->first()->setting_value;
                    $payload['subject_placeholders'] = array();
                    $payload['message_placeholders'] = array();
                    $payload['subject_placeholders']['[mobile_number]'] = $payload['mobile_number'];
                    $payload['message_placeholders']['[first_name]'] = $customer->first_name;
                    $payload['message_placeholders']['[amount]'] = $loan->amount_requested;
                    $customerName = $customer->first_name;
                    
                    if($customer->middle_name){
                        $customerName.=' '.$customer->middle_name;
                    }
                    if($customer->surname){
                        $customerName.=' '.$customer->surname;
                    }
                    $payload['message_placeholders']['[customer_name]'] = $customerName;
                    $payload['message_placeholders']['[mobile_number]'] = $payload['mobile_number'];
                }
            }else{
                $payload['response_string'] = 'Customer cannot borrow, '.$loanStatus['reason'];
                $payload['response_status'] = config('app.responseCodes')['loan_rejected'];
                $payload['command_status'] = config('app.responseCodes')['command_failed'];
            }
        }else{
            $payload['response_string'] = $responseString;
            $payload['response_status'] = config('app.responseCodes')['command_failed'];
            $payload['command_status'] = config('app.responseCodes')['command_failed'];
        }
        return $payload;
    }
    
    /**
     * Approve a loan whose status is pending
     * @param type $payload
     * @return type
     */
    public function approve_loan_application($payload){
        $responseString = '';
        $responseStatus = '';
        $commandStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        if(isset($payload['loan_id'])){
            $loan = Loan::where('id', $payload['loan_id'])->first();
            if($loan && $loan->status==config('app.responseCodes')['loan_pending']){
                $loan->status = config('app.responseCodes')['loan_approved'];
                $loan->save();
                $responseString = 'Loan approved';
                $responseStatus = config('app.responseCodes')['command_successful'];
                $commandStatus = config('app.responseCodes')['command_successful'];
            }else{
                $responseString = 'Loan cannot be approved';
                $responseStatus = config('app.responseCodes')['command_successful'];
                $commandStatus = config('app.responseCodes')['command_failed'];
            }
            
        }
        $payload['response_string'] = $responseString;
        $payload['response_status'] = $responseStatus;
        $payload['command_status'] = $commandStatus;
        return $payload;
    }
    public function reverse_loan_disbursal($payload){
        if(isset($payload['loan_id'])){
            $loan = Loan::where('id', $payload['loan_id'])->first();
            if($loan && $loan->status==config('app.responseCodes')['loan_disbursed']){
                $loan->status = config('app.responseCodes')['loan_approved'];
                $loan->save();
                $payload['response_string'] = 'Loan Disbursement reversed';
                $payload['response_status'] = config('app.responseCodes')['command_successful'];
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }
        }
        return $payload;
    }
    
    public function reject_loan_application($payload){
        if(isset($payload['loan_id'])){
            $loan = Loan::where('id', $payload['loan_id'])->first();
            if($loan && $loan->status!=config('app.responseCodes')['loan_disbursed']){
                $loan->status = config('app.responseCodes')['loan_rejected'];
                $loan->save();
                $payload['response_string'] = 'Loan rejected';
                $payload['response_status'] = config('app.responseCodes')['command_successful'];
                $payload['command_status'] = config('app.responseCodes')['command_successful'];
            }else{
                $payload['response_string'] = 'Loan not rejected';
                $payload['response_status'] = config('app.responseCodes')['command_successful'];
                $payload['command_status'] = config('app.responseCodes')['command_failed'];
            }
        }
        return $payload;
    }
    
    /**
     * Disburse a loan to client
     * @param type $payload
     * @return type
     */
    public function send_funds($payload){
        $responseString = '';
        $responseStatus = '';
        $commandStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        $loan = Loan::where('id', $payload['loan_id'])->first();
        //avoid double disbursement
        if($loan && $loan->status==config('app.responseCodes')['loan_approved']){
            $loan = $this->applyCharges($loan);
            $details=array();
            $details['amount'];
            
            //api to send cash here
            $app = \App::getFacadeRoot();
            $paymentService = $app->make('Message');
            $apiResponse = $paymentService->sendMoney($details);
           
            if($apiResponse){
                
                $now =Carbon::now()->toDateTimeString();
                $loan->status= config('app.responseCodes')['loan_disbursed'];
                $loan->date_disbursed = $now;
                $loan->save();
                $responseString = 'Loan sent to customer';
                $responseStatus = config('app.responseCodes')['command_successful'];
                $commandStatus = config('app.responseCodes')['command_successful'];
                $payload['send_notification'] = true;
                $payload['mobile_number'] = $loan->customer->mobile_number;
                $payload['email'] = $loan->customer->email;
            }else{
                $responseString = 'Loan not sent to customer';
                $responseStatus = config('app.responseCodes')['command_failed'];
                $commandStatus = config('app.responseCodes')['command_failed'];
                $payload['send_notification'] = true;
            }
        }else{
           $responseString = 'Loan does not exist or not approved';
           $responseStatus = config('app.responseCodes')['command_failed'];
           $commandStatus = config('app.responseCodes')['command_failed']; 
        }
        $payload['response_string'] = $responseString;
        $payload['response_status'] = $responseStatus;
        $payload['command_status'] = $commandStatus;
        return $payload;
    }
    
    /**
     * Offset a loan with the specified amount
     * @param type $payload
     * @return string
     */
    public function offset_loan($payload){
        $responseString = '';
        $responseStatus = '';
        $commandStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
        $payment = Payment::where('reference',$payload['reference'])->first();
        if(!$payment){
            if($customer){
                if(isset($payload['amount']) && $payload['amount']>0){
                    //offset each loan until all amount is replenished
                    if(count($customer->loans)){
                        $amount = floatval($payload['amount']);
                        $paymentReceived = 0;
                        foreach($customer->loans as $loan){
                            //only process disbursed loans
                            if($loan->status!=config('app.responseCodes')['loan_disbursed']){
                                continue;
                            }

                            $balance = $loan->total - $loan->paid;
                            $amountTodeduct = 0;
                            if($amount==0){
                                break;
                            }
                            if($amount >= $balance){
                                //just deduct the balance
                                $amountToDeduct = $balance;
                            }else{
                                //Deduct the whole amount
                                $amountToDeduct = $amount;
                            }
                            if($amountToDeduct > 0){
                                $loan->paid += $amountToDeduct;
                                if($loan->paid >= $loan->total){
                                    $loan->status= config('app.responseCodes')['loan_paid'];
                                }
                                if($loan->save()){
                                    $paymentReceived++;
                                    $amount-=$amountToDeduct;
                                    //record the payment
                                    $payment = new Payment([
                                        'customer_id'=>$customer->id,
                                        'amount'=>$amountToDeduct,
                                        'currency'=>'KES',
                                        'reference'=>$payload['reference'],
                                        'gateway'=>$payload['gateway'],
                                        'loan_id'=>$loan->id]);
                                    $payment->save();
                                }
                            }
                        }
                        if($paymentReceived > 0){
                            $responseStatus = config('app.responseCodes')['command_successful'];
                            $responseString ="Payment received";
                            $commandStatus  = config('app.responseCodes')['command_successful'];
                        }else{
                            $responseStatus = config('app.responseCodes')['command_failed'];
                            $responseString="Payment not received";
                            $commandStatus = config('app.responseCodes')['command_failed'];
                        }
                        if($amount > 0){
                            //there is an overpayment
                            $customer->withholding_balance += $amount;
                            $customer->save();
                           
                            if($amount == $payload['amount']){
                                //this is  a new payment and no loan was processed
                                $payment = new Payment([
                                            'customer_id'=>$customer->id,
                                            'amount'=>$payload['amount'],
                                            'currency'=>'KES',
                                            'reference'=>$payload['reference'],
                                            'gateway'=>$payload['gateway'],
                                            'loan_id'=>null]);
                                        $payment->save();
                            }
                            $responseStatus = config('app.responseCodes')['overpayment'];
                            $responseString="Overpayment amount sent to withholding account";
                            $commandStatus = config('app.responseCodes')['command_successful'];
                        }
                    }
                }else{
                    $responseStatus = config('app.responseCodes')['command_failed'];
                    $responseString="Invalid amount";
                    $commandStatus = config('app.responseCodes')['command_failed'];
                }
            }else{
                $responseStatus = config('app.responseCodes')['customer_does_not_exist'];
                $responseString="Customer does not exist";
                $commandStatus = config('app.responseCodes')['command_failed'];
            }
        }else{
            $responseStatus = config('app.responseCodes')['invalid_payment'];
            $responseString="Payment already used";
            $commandStatus = config('app.responseCodes')['command_successful'];
        }
        $payload['response_string'] = $responseString;
        $payload['response_status'] = $responseStatus;
        $payload['command_status'] = $commandStatus;
        return $payload;
    }
    public function send_notification($payload){
        $responseString = '';
        $responseStatus = '';
        $commandStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        if(isset($payload['send_notification']) && $payload['send_notification']){
           $payload['msisdn'] = $payload['mobile_number'];
           $payload['email'] = $payload['email'];
           if($this->responseProcessor->processResponse($payload)){
               $responseStatus = config('app.responseCodes')['command_successful'];
               $commandStatus = config('app.responseCodes')['command_successful'];
               $responseString="Notification sent";
           }else{
               $responseStatus = config('app.responseCodes')['command_failed'];
               $commandStatus = config('app.responseCodes')['command_failed'];
               $responseString="Notification not sent";
           }
        }
        $payload['response_string'] = $responseString;
        $payload['response_status'] = $responseStatus;
        $payload['command_status'] = $commandStatus;
        return $payload;
    }
    
    /**
     * Check if a customer is able to borrow
     * @param type $customer
     * @return boolean
     */
    public function customerCanBorrow($customer){
        $response = array();
        $response['can_borrow'] = false;
        $response['reason'] = '';
        //first let's check if customer is active
        if($customer->status==config('app.customerStatus')['active']){
            //if this customer belongs to an organization, check the status of the organization
            if($customer->organization_id && $customer->organization->status==config('app.customerStatus')['active']){
                $response['can_borrow'] = false;
                $response['reason'] = 'Organization disabled';
            }
            //then let check if customer has pending loans
            if(count($customer->loans)){
                $loanBalance= 0;
                $loanPaid = 0;
                $loanTotal=0;
                foreach($customer->loans as $loan){
                    $loanTotal += $loan->total;
                    $loanPaid +=$loan->paid;
                }
                $loanBalance=$loanTotal-$loanPaid;
                if($loanBalance > 0){
                    $response['can_borrow'] = false;
                    $response['reason'] = 'Outstanding balance';
                }
                //if there is a loan that is pending or waiting approval, cannot borrow
                if($loan->status==config('app.responseCodes')['loan_pending'] || $loan->status==config('app.responseCodes')['loan_approved']){
                    $response['can_borrow'] = false;
                    $response['reason'] = 'Existing Loan';
                }
            }else{
                //customer has no active loans
                $response['can_borrow'] = true;
                $response['reason'] = '';
            }
            
        }
        return $response;
    }
    public function applyCharges($loan){
        $fees = 0;
        $charges = 'nco_processing_fee';//default fee is for the non check off customers
        if($loan->customer->organization_id){
            $charges = 'co_processing_fee';
        }
        $fees = floatval($this->setting->where('setting_name',$charges)->first()->setting_value);
        $fixedCost =  	floatval($this->setting->where('setting_name','fixed_loan_cost')->first()->setting_value);
        $interest =  	floatval($this->setting->where('setting_name','loan_interest_rate')->first()->setting_value);
        $dailyInterest = ($interest/3000);
        $interestAndLoan = $dailyInterest * $loan->amount_requested;
        $feesAndLoan = ($fees/100)*$loan->amount_requested;
        $processedAmount = $loan->amount_requested +$feesAndLoan;
        $loan->daily_interest = $interestAndLoan;
        $loan->amount_processed = $processedAmount;
        $loan->fees = $fees;
        $loan->total = $interestAndLoan + $processedAmount;
        $loan->save();
        return $loan;
    }
    public function getCustomerStatement($payload){
        
    }
}


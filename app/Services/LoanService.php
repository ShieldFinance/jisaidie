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
            if($customer){
            $loanStatus = $this->customerCanBorrow($customer);
            if($loanStatus['can_borrow']){
                if(isset($payload['amount']) && $payload['amount'] >= $minimumAmount && $payload['amount'] <=$maximumLoan){
                    $loan = new Loan();
                    $loan->amount_requested = $payload['amount'];
                    $loan->customer_id = $customer->id;
                    $loan->type = isset($payload['type'])?$payload['type']:"co";
                    $loan->purpose = isset($payload['purpose'])?$payload['purpose']:'';
                    $loan->status = $loan->type=='nco'?config('app.loanStatus')['approved']:config('app.loanStatus')['pending'];
                    $loan->save();
                    $payload['loan_id'] = $loan->id;
                    $payload['response_string'] = 'Loan Created';
                    $payload['response_status'] = config('app.responseCodes')['command_successful'];
                    $payload['command_status'] = config('app.responseCodes')['command_successful'];
                    $payload['send_notification'] = true;
                    $payload['email'] = $this->setting->where('setting_name','new_loan_application_recipients')->first()->setting_value;
                    $payload['subject_placeholders'] = array();
                    $payload['message_placeholders'] = array();
                    $payload['subject_placeholders']['[mobile_number]'] = $payload['mobile_number'];
                    $payload['message_placeholders']['[customer_name]'] = $customer->surname;
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
                 $payload['response_string'] = 'Customer does not exist';
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
            if($loan && $loan->status==config('app.loanStatus')['pending']){
                $loan->status = config('app.loanStatus')['approved'];
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
            if($loan && $loan->status==config('app.loanStatus')['disbursed']){
                $loan->status = config('app.loanStatus')['approved'];
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
            if($loan && $loan->status!=config('app.loanStatus')['disbursed']){
                $loan->status = config('app.loanStatus')['rejected'];
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
        if($loan && $loan->status==config('app.loanStatus')['approved']){
            $loan = $this->applyCharges($loan);
            //api to send cash here 
            $apiResponse = true;
            if($apiResponse){
                $now =Carbon::now()->toDateTimeString();
                $loan->status= config('app.loanStatus')['disbursed'];
                $loan->date_disbursed = $now;
                $loan->save();
                $responseString = 'Loan sent to customer';
                $responseStatus = config('app.responseCodes')['command_successful'];
                $commandStatus = config('app.responseCodes')['command_successful'];
                $payload['message_placeholders'] = array();
                $payload['message_placeholders']['[customer_name]'] = $loan->customer->surname;
                $payload['send_notification'] = true;
                $payload['send_now'] = true;
                $payload['mobile_number'] = $loan->customer->mobile_number;
                $payload['gateway'] =  'mpesa';
                $payload['email'] = $loan->customer->email;
                $app = \App::getFacadeRoot();
                $paymentService = $app->make('Payment');
                $paymentResponse = $paymentService->sendMoney($payload);
                $loan->payment_response = json_encode($paymentResponse);
                if(isset($paymentResponse[0]) && $paymentResponse[0]->status=='Queued'){
                    $loan->payment_status = $paymentResponse[0]->status;
                    $loan->transaction_fee = $paymentResponse[0]->transactionFee;
                    $loan->transaction_ref  = $paymentResponse[0]->transactionId;
                    $loan->provider = $paymentResponse[0]->provider;
                }
                $loan->save();
                
            }else{
                $responseString = 'Loan not sent to customer';
                $responseStatus = config('app.responseCodes')['command_failed'];
                $commandStatus = config('app.responseCodes')['command_failed'];
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
    public function offset_loan($payload){
        $response = [];
        $responseString = '';
        $responseStatus = '';
        $commandStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        if(isset($payload['mobile_number']) && isset($payload['payment_id'])){
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            $payment = Payment::find($payload['payment_id']);
            $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
            $payment = Payment::find($payload['payment_id']);
            
            if($customer){
                $amountToDeduct = 0;
                $overPayment = 0;
                $loan = Loan::where('customer_id',$customer->id)->orderBy('id','desc')->first();
                $loanBalance = $loan->total - $loan->paid;
                if($loanBalance >= $payment->amount){
                    $amountToDeduct = $payment->amount;
                }else{
                    $amountToDeduct = $loanBalance;
                    //put overpayment to withholding account
                    $customer->withholding_balance += $payment->amount - $amountToDeduct;
                    $customer->save();
                }
                $loan->paid+=$amountToDeduct;
                $payment->loan_id = $loan->id;
                if($loan->paid >= $loan->total){
                    $loan->status= config('app.loanStatus')['paid'];
                }
                $loan->save();
                $payment->save();
            }
            $response['mobile_number'] = $customer->mobile_number;
            $response['email'] = $customer->email;
            $response['send_notification'] = true;
            $response['send_now'] = true;
            $response['service_id'] = $payload['service_id'];
            $response['message_placeholders'] = array();
            $response['message_placeholders']['[customer_name]'] = $customer->surname;
            $response['message_placeholders']['[amount]'] = $payment->amount;
            $responseString = 'Payment received';
            $responseStatus = config('app.responseCodes')['command_successful'];
            $commandStatus = config('app.responseCodes')['command_successful'];
            
        }else{
            $responseStatus = 'Missing parameters';
            $responseStatus = config('app.responseCodes')['command_failed'];
            $commandStatus = config('app.responseCodes')['command_failed'];
        }
        $response['response_status'] = $responseStatus;
        $response['respone_string']=$responseString;
        $response['command_status'] = $commandStatus;
        return $response;
    }
    /**
     * Offset a loans with the specified amount
     * @param type $payload
     * @return string
     */
    public function offset_loans($payload){
        $responseString = '';
        $responseStatus = config('app.responseCodes')['no_response'];
        $commandStatus = config('app.responseCodes')['command_failed'];
        $customer = Customer::where('mobile_number',$payload['mobile_number'])->first();
        $payment = Payment::find($payload['payment_id']);
        if($payment){
            if($customer){
                if(isset($payload['amount']) && $payload['amount']>0){
                    //offset each loan until all amount is replenished
                    if(count($customer->loans)){
                        $amount = floatval($payload['amount']);
                        $paymentReceived = 0;
                        foreach($customer->loans as $loan){
                            //only process disbursed loans
                            if($loan->status!=config('app.loanStatus')['disbursed']){
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
                                    $loan->status= config('app.loanStatus')['paid'];
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
            $responseString="Payment not found";
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
            if($customer->organization_id && $customer->organization->status!=config('app.customerStatus')['active']){
                $response['can_borrow'] = false;
                $response['reason'] = 'Organization disabled';
                return $response;
            }
            $response['can_borrow'] = true;
            //then let check if customer has pending loans
            if(count($customer->loans)){
                $loanBalance= 0;
                $loanPaid = 0;
                $loanTotal=0;
                foreach($customer->loans as $loan){
                    if($loan->status== config('app.loanStatus')['disbursed'] || $loan->status==config('app.loanStatus')['locked']){
                        $loanTotal += $loan->total;
                        $loanPaid +=$loan->paid;
                    }
                }
                $loanBalance=$loanTotal-$loanPaid;
                if($loanBalance > 0){
                    $response['can_borrow'] = false;
                    $response['reason'] = 'Outstanding balance';
                    return $response;
                }
                //if there is a loan that is pending or waiting approval, cannot borrow
                if($loan->status==config('app.loanStatus')['pending'] || $loan->status==config('app.loanStatus')['approved'] || $loan->status==config('app.loanStatus')['locked']){
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
        $loanTotal = $interestAndLoan + $processedAmount;
        $loan->daily_interest = $interestAndLoan;
        $loan->amount_processed = ceil($processedAmount);
        $loan->fees = $fees;
        $loan->total = ceil($loanTotal);
        $loan->save();
        return $loan;
    }
    public function getCustomerStatement($payload){
        
    }
}


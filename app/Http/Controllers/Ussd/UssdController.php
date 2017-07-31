<?php

namespace App\Http\Controllers\Ussd;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Session;
use App\Http\Models\Ussd;
use App\Http\Models\Customer;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ServiceProcessor;
class UssdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $ussd = Ussd::where('id', 'LIKE', "%$keyword%")
				->orWhere('sessionId', 'LIKE', "%$keyword%")
				->orWhere('serviceCode', 'LIKE', "%$keyword%")
				->orWhere('pin_verified', 'LIKE', "%$keyword%")
				->orWhere('is_pin_change', 'LIKE', "%$keyword%")
				->orWhere('level', 'LIKE', "%$keyword%")
				->orWhere('action', 'LIKE', "%$keyword%")
				->orWhere('no_net_salary', 'LIKE', "%$keyword%")
				->orWhere('is_new', 'LIKE', "%$keyword%")
				->orWhere('is_terms', 'LIKE', "%$keyword%")
				->orWhere('is_statement', 'LIKE', "%$keyword%")
				->orWhere('client_name', 'LIKE', "%$keyword%")
				->orWhere('net_salary', 'LIKE', "%$keyword%")
				->orWhere('advance_amount', 'LIKE', "%$keyword%")
				->orWhere('company', 'LIKE', "%$keyword%")
				->orWhere('manager', 'LIKE', "%$keyword%")
				->orWhere('manager_mobile', 'LIKE', "%$keyword%")
				->orWhere('employee_count', 'LIKE', "%$keyword%")
				->orWhere('phoneNumber', 'LIKE', "%$keyword%")
				->orWhere('text', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $ussd = Ussd::paginate($perPage);
        }

        return view('ussd.ussd.index', compact('ussd'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('ussd.ussd.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'id' => 'required'
		]);
        $requestData = $request->all();
        
        Ussd::create($requestData);

        Session::flash('flash_message', 'Ussd added!');

        return redirect('ussd/ussd');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $ussd = Ussd::findOrFail($id);

        return view('ussd.ussd.show', compact('ussd'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $ussd = Ussd::findOrFail($id);

        return view('ussd.ussd.edit', compact('ussd'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
			'id' => 'required'
		]);
        $requestData = $request->all();
        
        $ussd = Ussd::findOrFail($id);
        $ussd->update($requestData);

        Session::flash('flash_message', 'Ussd updated!');

        return redirect('ussd/ussd');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Ussd::destroy($id);

        Session::flash('flash_message', 'Ussd deleted!');

        return redirect('ussd/ussd');
    }
	
	//new version ussd2.0
	public function processRequest()
	{
		//error_reporting(E_ALL);
		//	if (!ini_get('display_errors'))
		//	{
		//		ini_set('display_errors', 1);
		//	}
		$data=array();
		
		// Reads the variables sent via POST from our gateway
		$data['sessionId']   = $_REQUEST["sessionId"];
		$data['serviceCode'] = $_POST["serviceCode"];
		$data['phoneNumber'] = $_POST["phoneNumber"];
		$data['text']        = $_POST["text"];
		$data['created_on']        =date('Y-m-d h:i:s');
		$data['phoneNumber']= ltrim ($data['phoneNumber'], '+');
		$textarray=explode("*",$data['text']);
		$mobile=  str_pad(substr($data['phoneNumber'],3),10,0,STR_PAD_LEFT);
		
		$existing_session=Ussd::where('sessionId',$data['sessionId'])->first();
		if(empty($existing_session)){
			Ussd::create($data);
		}
		
		$existing_session=Ussd::where('sessionId',$data['sessionId'])->firstOrFail();
				
		$customer_exist=Customer::where('mobile_number',$data['phoneNumber'])->first();		
		
		
		$response="END Sorry,A technical error occured,Kindly check with us later.";
		//check if its an existing account else apply online
		if(empty($customer_exist)){
		  $response  = "END Your number is not registered in our system.please register online at www.shield.co.ke \n";	
		}else{
		  $account=Customer::where('mobile_number',$data['phoneNumber'])->firstOrFail();
		 
		  if(!$account->organization_id){
			 $response  = "END You have not been assisgned to an organization. Please contact your HR for assistance. \n";	
		
		  }else{
			
			if($data['text'] ==""){	//first call
				    //check if user has set pin
					if($account->pin_hash!=0){
						$response  = "CON Dear customer, welcome to your Shield account. Please enter your Shield PIN.  Forgot your PIN? Call 0786 798 822 \n";
					}else{
						$response  = "CON Dear customer, welcome to Shield. Please set your Shield PIN (4 characters[0-9]) \n";
					}
			}else if($existing_session->level==0){
				if((end($textarray)==0 && strlen(end($textarray))==1) && strlen(end($textarray))==3){
					$response=$this->ExitApp();
				}else if(((end($textarray)==0 && strlen(end($textarray))==2) || (end($textarray)==6) && $existing_session->pin_verified!=-1)){
					
					$response=$this->ExitApp();
				}else{
				  $response=$this->pinVerification($textarray,$existing_session,$account);
				}
				
			}else if($existing_session->level==1){
				if(end($textarray)==0 && strlen(end($textarray))==3){
					$response=$this->ExitApp();
				}else{
					switch(end($textarray)){
						case 1:
							$response=$this->applyAdvance($textarray,$existing_session,$account);
							break;
						case 2:
							
							$response=$this->checkAdvanceStatus($textarray,$existing_session,$account);
							break;
						case 3:
							$response=$this->sendMiniStatement($textarray,$existing_session,$account);
							break;
						case 4:
							$response=$this->promptNewPin($textarray,$existing_session,$account);
							break;
						case 5:
							$response=$this->showTC($textarray,$existing_session,$account);
							break;
						case 6:
							$response=$this->ExitApp();
							break;
						default:
							$data = array(
								'level' =>1,
								'action' =>0
							  );
							USSD::find($existing_session->id)->update($data);
							$response=$this->getMainMenu();
							break;
					}
				}
				
			}else if($existing_session->level==2){
				if(end($textarray)==0 && strlen(end($textarray))==3){
					$response=$this->ExitApp();
				}else{
					switch($existing_session->action){
						case 1:
							$response=$this->registerAdvanceApplication($textarray,$existing_session,$account);
							break;
						case 2:
						    $this->setNetsalary($textarray,$existing_session,$account);
							$response=$this->applyAdvance($textarray,$existing_session,$account);
							break;
					   case 4:
							$response=$this->changePin($textarray,$existing_session,$account);
							break;
						default:
							$data = array(
								'level' =>0,
								'action' =>0
							  );
							  USSD::find($existing_session->id)->update($data);
							$response=$this->getMainMenu();
							break;
					}
				}
			}
		  }
		 
		}
		echo $response;exit;
	}
	public function setNetsalary($textarray,$existing_session,$account){
		$data = array(
		'net_salary'		=> end($textarray),
			);
		Customer::find($account->id)->update($data);
		
	}
	public function showTC(){
		$response  = "CON  Visit http://shield.co.ke/ for our terms and conditions \n";
		$response .= "00. Back \n";
		$response .= "000. Exit \n";
		return $response;
	}
	public function promptNewPin($textarray,$existing_session,$account){
		
		$data = array(
			 'level' =>2,
			 'action' =>4
		   );
		USSD::find($existing_session->id)->update($data);
		$response  = "CON Enter your new secret pin(4 characters[0-9]) \n";
		$response .= "00. Back \n";
		$response .= "000. Exit \n";
		return $response;
	}
	public function changePin($textarray,$existing_session,$account){
		
		if(end($textarray)==00){
		  $data = array(
			'level' =>1,
			'action' =>0
		  );
		  USSD::find($existing_session->id)->update($data);
		  $response=$this->getMainMenu();
		}else{
		$pin = $this->auth->hash_password(end($textarray));
		$pin_hash = $pin['hash'];
		
		$data = array(
			'pin_hash'		=> $pin_hash,
				);

		Customer::find($account->id)->update($data);
		$response  = "CON Your pin has been changed.  \n";
		
		  $data = array(
			'level' =>1,
			'action' =>0
		  );
		  USSD::find($existing_session->id)->update($data);
		  $response.=$this->getMainMenu2();
		
		}
		return $response;
	}
	public function sendMiniStatement($textarray,$existing_session,$account){
		$mobile=  str_pad(substr($existing_session->phoneNumber,3),10,0,STR_PAD_LEFT); 
		//error_reporting(E_ALL);
		//if (!ini_get('display_errors'))
		//{
		//	ini_set('display_errors', 1);
		//} 						
		$status=array();
		$status[1]="PLACED";
		$status[2]="PENDING APPROVAL";
		$status[3]="DECLINED";
		$status[4]="APPROVED";
		$status[5]="DISBURSED";
		$status[6]="SERVICED";
		
		
		$advances = $this->loan_model->select('id,amount_requested,total,created_on, disbursed_on,company,status,name')
		 ->where('mobile', $mobile)
		 ->limit(1,0)
		 ->order_by("id","DESC")
		 ->find_all();
		 
		$message="";
			if (strtotime($advances[0]->created_on) < strtotime($advances[0]->disbursed_on)) {
			$date=date("Y-M-d H:i:s",strtotime($advances[0]->disbursed_on));
			}else{
			$date=date("Y-M-d H:i:s",strtotime($advances[0]->created_on));	
			}
			$message.=$date." \nAdvance Amount KES ".number_format($advances[0]->amount_requested,2)."\n";
			$message.="Due Amount KES ".number_format($advances[0]->total,2)."\n";
			$message.="Transaction status ".$status[$advances[0]->status]."\n";
		
		
		
		//queue message
		$id=0;
		if($advances[0]->id)
		$id=Services::queueMessage($message,"+".$existing_session->phoneNumber,"account_statement",$advances[0]->company,$existing_session->sessionId);
		
				
		if($id){
		 Services::sendMessage($id);
		 $response  = "CON Your mini statement will be sent to your mobile phone shortly. \n";
		 $response .= "00. Back \n";
		 $response .= "000. Exit \n";
		 
		}else{
		 $response  = "CON Dear customer, You do not have any outstanding balance with shield.Thank you . \n";
		 $response .= "00. Back \n";
		 $response .= "000. Exit \n";
		}
		return $response;
	}
	public function checkAdvanceStatus($textarray,$existing_session,$account){
		  $mobile=  str_pad(substr($existing_session->phoneNumber,3),10,0,STR_PAD_LEFT);
		  /*statuses
			*2-PENDING APPROVAL
			*3-DECLINED
			*4-APPROVED
			*5-DISBURSED
			*6-SERVICED
			*7-SERVICED
			*/
		   $status=array();
		  
		   $status[2]="PENDING APPROVAL";
		   $status[3]="DECLINED";
		   $status[4]="APPROVED";
		   $status[5]="DISBURSED";
		   $status[6]="SERVICED";
		   
		//get customer loan status  //5 paid
		
		$app = \App::getFacadeRoot();
		$paymentService = $app->make('CheckCustomerStatus');
		$apiResponse = $paymentService->checkID(["mobile_number"=>$mobile]);
		
		echo '<pre>';print_r($apiResponse);exit;
		
		if(!empty($lastLoandata)){			
						   
			$response= "CON Date: ".$lastLoandata[0]->created_on."  \n";
			$response.= "Amount applied: KES ".number_format($lastLoandata[0]->amount_requested,2)." \n";
			$response.= "Status: ".$status[$lastLoandata[0]->status]." \n";
			$response.= "Amount due: KES ".number_format($lastLoandata[0]->total-$lastLoandata[0]->paid,2)." \n";
			$response .= "00. Back \n";
			$response .= "000. Exit \n";
		}else{
			$response="CON Dear Customer, You have not applied for any advance with shield.Kindly proceed to apply for an advance.";
			$response .= "00. Back \n";
			$response .= "000. Exit \n";
		}
		return $response;
	}
	public function registerAdvanceApplication($textarray,$existing_session,$account){
		$mobile= $existing_session->phoneNumber;
		//user chooses to go back to the main menu
		if(end($textarray)==00){
		  $data = array(
			'level' =>1,
			'action' =>0
		  );
		  USSD::find($existing_session->id)->update($data);
		  $response=$this->getMainMenu();
		}else{
			   //check if amount entered is less than half
			   
			   if($account->net_salary/2 >= end($textarray) && end($textarray)>=500){
						//register the advance amount on dashboard
						if($account->status && $account->organization_id && $account->is_checkoff){
																
							
							$loandata = array();
							$company=$this->company_model->find($account->company);
								//echo '<pre>';print_r($company);exit;
								if($company->self_approval==1){
								  $loandata['status']        = 2;	
								}else{
								  $loandata['status']        = 4;
								}
								
								$loandata['name']        = $account->display_name;
								$loandata['mobile']        = $mobile;
								$loandata['amount']        = end($textarray);
								
								
								$loandata['salary']        = $account->net_salary;
								$loandata['company']        = $account->company;
								   //$this->settings_lib->item('site.interest_rate');
								   //$this->settings_lib->item('site.processing_cost');
								$loandata['amount_requested']=end($textarray);
								  
								$interest_rate=$this->settings_lib->item('site.interest_rate');
								   
								$processing_cost=$this->settings_lib->item('site.processing_cost');
								   
								$loandata['interest']= $loandata['amount']*($interest_rate/3000);
														
								$loandata['amount']=end($textarray)+(40+(end($textarray)*$processing_cost/100));
								 
								$loandata['total']= $loandata['amount'];
								   
								
								$id = $this->loan_model->skip_validation(true)->insert($loandata);
								
								if($id){
								  if($company->self_approval==1){
									Services::_email_request_approval($id);
								   }else{
									 Services::_approve($id,true);
								   }
								  $response  = "CON  Thank you for applying for a salary advance from Shield, you will receive the advance amount on your phone. \n";
								  $response .= "00. Back \n";
								  $response .= "000. Exit \n";
								}else{
								  $response  = "END Sorry!There was an error with your advance application.Try again later.\n";
								}
										
							
						}else{
						   $response  = "END Dear Customer, your account is not active.  \n";
						}
				
			   }else{
				$response  = "CON  Kindly enter amount that is atleast KES.500 and less than or equal to KES.".($account->net_salary/2)." . \n";
				$response .= "00. Back \n";
				$response .= "000. Exit \n";
			   }
				
			
		}
		
		return $response;
	}
	
	public function applyAdvance($textarray,$existing_session,$account){
		 
		 //user chooses to go back to the main menu
		
		if(end($textarray)==00){
		  $data = array(
			'level' =>1,
			'action' =>0
		  );
		  USSD::find($existing_session->id)->update($data);
		  $response=$this->getMainMenu();
		}else{
		  
		  $app = \App::getFacadeRoot();
		  $paymentService = $app->make('Customer');
		  $apiResponse = $paymentService->check_customer_status(["mobile_number"=>$existing_session->phoneNumber]);
		
		
		   if(empty($apiResponse['can_borrow']['can_borrow'])){
						
						    if($account->net_salary==0){
								
								 $data = array(
									'level' =>2,
									'action' =>2
								);
								USSD::find($existing_session->id)->update($data);
								$response  = "CON Enter your NET salary. Please DO NOT use commas \n";
								$response .= "00. Back \n";
								$response .= "000. Exit \n";
							}else{
								  $data = array(
									'level' =>2,
									'action' =>1
								  );
								  USSD::find($existing_session->id)->update($data);
								    $qualified_amount=($account->net_salary/2);
									if($qualified_amount>65000){
										$qualified_amount=65000;
									}
									$response  = "CON Enter salary advance amount, you qualify for KES ".number_format($qualified_amount,2).". Please DO NOT use commas \n";
								  $response .= "00. Back \n";
								  $response .= "000. Exit \n";
							}
			}else{
		  
		  
				  if($lastLoandata[0]->status==6 || $lastLoandata[0]->status==3 ){
					$data = array(
					'level' =>2,
					'action' =>1
				  );
				  USSD::find($existing_session->id)->update($data);
				  $qualified_amount=($account->net_salary/2);
				   if($qualified_amount>65000){
					   $qualified_amount=65000;
				   }
				  $response  = "CON Enter salary advance amount, you qualify for KES ".number_format($qualified_amount,2).". Please DO NOT use commas \n";
				  $response .= "00. Back \n";
				  $response .= "000. Exit \n";
				  
				}else if($lastLoandata[0]->status<=2 ){
					$response  = "CON Dear Customer, your previous advance of KES ".number_format($lastLoandata[0]->amount_requested,2)." is still pending approval from your Employer \n";
					$response .= "00. Back \n";
					$response .= "000. Exit \n";
				}else{
					$response  = "CON Dear customer, you already have an outstanding advance of KES ".number_format($lastLoandata[0]->total,2).". Kindly pay this amount to qualify for another advance. \n";
					$response .= "00. Back \n";
					$response .= "000. Exit \n";
				}
			}
		}
		return $response;
	}
	public function pinVerification($textarray,$existing_session,$account){
		if($account->pin_hash!=""){//user just entered pin to login so proceed with menu after verification
					
					
						if (Hash::make(end($textarray))==$account->pin_hash){
							$data = array(
                               'pin_verified' =>1
                             );
				             USSD::find($existing_session->id)->update($data);
							
							$response=$this->getMainMenu();
							
							//update level to 1
							$data = array(
                               'level' =>1
                            );
				            USSD::find($existing_session->id)->update($data);
						 
						}else{
						    $response  = "CON The PIN you entered was incorrect. Please check and try again.\n";
							$response .= "00. Exit \n";
							$data = array(
                               'level' =>0,
							   'pin_verified' =>-1
                            );
				            USSD::find($existing_session->id)->update($data);
							
						}
					 }else if($existing_session->pin_verified){
						
						
					 }else{//set users new pin and display menu
						
					
						
						$pin_hash =Hash::make(end($textarray));
						
						Customer::find($account->id)->update(['pin_hash' => $pin_hash]);
						USSD::find($existing_session->id)->update(['pin_verified' => 1]);
						
						$response=$this->getMainMenu();
						//update level to 1
						USSD::find($existing_session->id)->update(['level' => 1]);
						
					 }
		return $response;
	}
	public function ExitApp(){
	   $response="END Thank you. For customer care call 0786 798 822";
	   return $response;	
	}
	public function getMainMenu(){
		$response  = "CON 1. Apply for an advance \n";
		$response .= "2. Check advance status \n";
		$response .= "3. Mini statement \n";
		$response .= "4. Change pin \n";
		$response .= "5. Terms & Conditions \n";
		$response .= "6. Exit \n";
		return $response;
	}
	public function getMainMenu2(){
		$response  = "1. Apply for an advance \n";
		$response .= "2. Check advance status \n";
		$response .= "3. Mini statement \n";
		$response .= "4. Change pin \n";
		$response .= "5. Terms & Conditions \n";
		$response .= "6. Exit \n";
		return $response;
	}
}

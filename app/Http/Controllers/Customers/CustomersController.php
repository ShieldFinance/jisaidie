<?php

namespace App\Http\Controllers\Customers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProcessor;
use App\Http\Models\Customer;
use App\Http\Models\CustomerDevice;
use App\Http\Models\Message;
use Illuminate\Http\Request;
use Session;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    { 
        $payload = array();
        $payload['keyword'] = $request->get('search');
        $service = $request->get('service');
        $customers = $this->getCustomers($payload);

        return view('admin/customers.customers.index', compact('customers'));
    }
    
    
    
    public function resetPin(Request $request){
        
        $payload = array();
        $customers = $this->getCustomers($payload);
        $serviceProcessor = new ServiceProcessor();
        $customer = Customer::find($request->get('customer_id'));
        $device = CustomerDevice::where('customer_id',$customer->id)
        ->orderBy('id','desc')
        ->first();
        if(isset($device->registration_token)){
            $details = array('mobile_number'=>$customer->mobile_number);
            $request->request->add(['action' => 'ResetPin','request'=>json_encode($details)]);
            $response = $serviceProcessor->doProcess($request);
			
            if(isset($response['send_notification']) && isset($response['send_notification']['sent'])){
                Session::flash('flash_message', 'Pin reset sent request!');
            }else{
                Session::flash('flash_message', 'Pin reset failed!');
            }
            
        }else{
             Session::flash('flash_message', 'Customer device not registered!');
        }
        return view('admin/customers.customers.index', compact('customers'));
    }
    public function activate(Request $request)
    {
		$response = Customer::find($request->get('customer_id'))->update(['status' => 1]);
		
		Session::flash('flash_message', 'Customer activated!');

        return redirect('admin/customers');
    }
	public function deactivate(Request $request)
    {
		$response = Customer::find($request->get('customer_id'))->update(['status' => 0]);
        
       
		Session::flash('flash_message', 'Customer activated!');

        return redirect('admin/customers');
    }
	public function verify(Request $request)
    {
		
		$customer = Customer::find($request->get('customer_id'));
        $details=array();
		$details['customer_id']=$customer->id;
        $details['id_number']=$customer->id_number;
		$details['first_name']=$customer->surname;
		$details['middle_name']=$customer->other_name;
		$details['last_name']=$customer->last_name;
		
		if(!empty ( $customer->id_number)){
			
		 //api to check id with the crb
		    $app = \App::getFacadeRoot();
			$paymentService = $app->make('Crb');
			$apiResponse = $paymentService->checkID($details);
		
			if($apiResponse["match"]==1){
				  Session::flash('flash_message',$apiResponse["code"]." : ". $apiResponse["message"]);
			}else{
				  Session::flash('flash_message',$apiResponse["code"]." : ". $apiResponse["message"]);
			}
		}else{
			 Session::flash('flash_message',"Customer ID Number is empty");
		}
		
        
      

        return redirect('admin/customers');
    }
    public function getCustomers($payload){
        $perPage = 25;
        if(isset($payload['perPage'])){
            $perPage=$payload['perPage'];
        }
        if (isset($payload['keyword'])) {
            $customers = Customer::where('first_name', 'LIKE', "%$keyword%")
				->orWhere('middle_name', 'LIKE', "%$keyword%")
				->orWhere('surname', 'LIKE', "%$keyword%")
				->orWhere('mobile_number', 'LIKE', "%$keyword%")
				->orWhere('employee_number', 'LIKE', "%$keyword%")
				->orWhere('id_number', 'LIKE', "%$keyword%")
				->orWhere('net_salary', 'LIKE', "%$keyword%")
				->orWhere('email', 'LIKE', "%$keyword%")
				->orWhere('is_checkoff', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->orWhere('activation_code', 'LIKE', "%$keyword%")
				->orWhere('organization_id', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $customers = Customer::paginate($perPage);
        }
        return $customers;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/customers.customers.create');
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
			'first_name' => 'required',
			'last_name' => 'required',
			'id_number' => 'required',
			'net_salary' => 'required',
			'organization_id' => 'required'
		]);
        $requestData = $request->all();
        
        Customer::create($requestData);

        Session::flash('flash_message', 'Customer added!');

        return redirect('admin/customers');
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
        $customer = Customer::findOrFail($id);

        return view('admin/customers.customers.show', compact('customer'));
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
        $customer = Customer::findOrFail($id);

        return view('admin/customers.customers.edit', compact('customer'));
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
			'first_name' => 'required',
			'last_name' => 'required',
			'id_number' => 'required',
			'net_salary' => 'required',
			'organization_id' => 'required'
		]);
        $requestData = $request->all();
        
        $customer = Customer::findOrFail($id);
        $customer->update($requestData);

        Session::flash('flash_message', 'Customer updated!');

        return redirect('admin/customers');
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
        Customer::destroy($id);

        Session::flash('flash_message', 'Customer deleted!');

        return redirect('admin/customers');
    }
}

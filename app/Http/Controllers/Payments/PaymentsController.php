<?php

namespace App\Http\Controllers\Payments;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProcessor;
use App\Http\Models\Payment;
use App\Http\Models\Loan;
use Illuminate\Http\Request;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends Controller
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
        $action_buttons = $this->getActionButtons();
        $search_date_from = $request->get('search_date_from');
        $search_date_to = $request->get('search_date_to');
        $status = $request->get('search_status');
        $type = $request->get('search_type');
        $service = $request->get('service');
        if(!empty($service) && $service=='ExportPayments'){
            $this->export($request);
        }
        $wheres = array();
        $orWheres = array();
        if (!empty($keyword)) {
            $orWheres[] =  ['payments.reference' ,'LIKE',"%$keyword%"];
            $orWheres[] =  ['payments.loan_id' ,'=',$keyword];
            $orWheres[] =  ['payments.mobile_number' ,'LIKE',"%$keyword%"];
            $orWheres[] =  ['payments.provider_reference' ,'LIKE',"%$keyword%"];
            $payments = DB::table('payments')
                ->leftJoin('customers as c', 'c.mobile_number', '=', 'payments.mobile_number')
                ->where('payments.mobile_number' ,'LIKE',"%$keyword%")
                ->orWhere('payments.reference' ,'LIKE',"%$keyword%")
                ->orWhere('payments.provider_reference' ,'LIKE',"%$keyword%")
                ->select('payments.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
                ->orderBy('id','desc')->paginate($perPage);
            $request->session()->put('customers', $payments);
            return view('payments.payments.index', compact('payments','action_buttons'));
        } 
        if(!empty($search_date_from)){
            $time = strtotime($search_date_from);
            $timeFrom = date('Y-m-d H:i:s',$time);
            if(!empty($search_date_to)){
                $time = strtotime($search_date_to);
                $timeTo = date('Y-m-d H:i:s',$time);
                $wheres[]=['payments.created_at','>=',$timeFrom];
                $wheres[]=['payments.created_at','<=', $timeTo];
            }else{
                Session::flash('flash_message','You must specify both start and end date');
            }
        }
        if(!empty($status)){
            $wheres[] = ['payments.status','=', $status];
        }
        if(!empty($type)){
            $wheres[] = ['payments.type','=', $type];
        }
        if(empty($wheres)){
            $wheres[] = ['payments.id','>',0];
        }
        $payments = DB::table('payments')
                ->leftJoin('customers as c', 'c.mobile_number', '=', 'payments.mobile_number');
        if(!empty($wheres)){
         $payments  =  $payments->where($wheres);
        }
        if(!empty($orWheres)){
         $payments  =  $payments->orWhere($orWheres);
        }
                $payments  =  $payments->select('payments.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'));
                $payments  =  $payments->orderBy('id','desc')->paginate($perPage);
        
        $request->session()->put('payments', $payments);
        return view('payments.payments.index', compact('payments','action_buttons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('payments.payments.create');
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
			'amount' => 'required',
			'curreny' => 'required',
			'reference' => 'required',
			'gateway' => 'required',
			'loan_id' => 'required'
		]);
        $requestData = $request->all();
        
        Payment::create($requestData);

        Session::flash('flash_message', 'Payment added!');

        return redirect('admin/payments');
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
        $payment = Payment::findOrFail($id);

        return view('payments.payments.show', compact('payment'));
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
        $payment = Payment::findOrFail($id);

        return view('payments.payments.edit', compact('payment'));
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
			'amount' => 'required',
			'curreny' => 'required',
			'reference' => 'required',
			'gateway' => 'required',
			'loan_id' => 'required'
		]);
        $requestData = $request->all();
        
        $payment = Payment::findOrFail($id);
        $payment->update($requestData);

        Session::flash('flash_message', 'Payment updated!');

        return redirect('admin/payments');
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
        Payment::destroy($id);

        Session::flash('flash_message', 'Payment deleted!');

        return redirect('admin/payments');
    }
    public function export(Request $request) {
        Excel::create('Payments-' . date('Y-m-d'), function($excel) use($request) {
            $payments = $request->session()->get('payments');
            $data = array();
            $headers = array(
                'Amount',
                'Mobile Number',
                'Customer Name',
                'AT Reference',
                'Provider Reference',
                'Status',
                'Type',
                'Date'
            );
            $data[] = $headers;
            foreach ($payments as $payment) {
                $c = array();
                $c['amount'] = $payment->amount;
                $c['mobile number'] = $payment->mobile_number;
                $c['customer_name'] = $payment->customer_name;
                $c['at_reference'] = $payment->reference;
                $c['provider_reference'] = $payment->provider_reference;
                $c['status'] = $payment->status;
                $c['type'] = $payment->type;
                $c['date'] = $payment->created_at;
                $data[] = $c;
            }

            $excel->sheet('Loan', function($sheet) use ($data) {

                $sheet->fromArray($data, null, 'A1', false, false);
            });
        })->download('xls');
    }
     public function getActionButtons() {
        $user = Auth::user();
        $action_buttons = '';
        if ($user) {
            $userIsAdmin = Auth::user()->hasRole('Super Admin');

            if ($user->can('can_export_payments') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                      <a href="javascript:void(0)" data-action='ExportPayments' class="btn btn-info btn-sm export_payments" title="Export Payments">
                            <i class="fa fa-download" aria-hidden="true"></i> Export to excel
                        </a>
ACTIONS;
            }
            
            
$status = '<option value="Success">Success</option>
         <option value="Failed">Failed</option>';
$html = <<<popover
        <div id="popover-content" class="">
<form method="GET" action="/admin/payments" class="search_form" role="search">
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Search...">
        
    </div>
<div class="input-group">
    <label for="">Status</label>
     <select class="form-control" name="search_status">
         <option value="">Select</option>
         $status
     </select>
    </div>
        <div class="input-group">
    <label for="">Type</label>
     <select class="form-control" name="search_type">
         <option value="">Select</option>
         <option value="credit">Credit</option>
        <option value="debit">Debit</option>
     </select>
    </div>
<div class="form-group">
    <label>Date From</label>
    <div class="input-group">
        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <input type="text" name="search_date_from" id="date_from" class="form-control">
    </div>
</div>
<div class="form-group">
    <label>Date To</label>
     <div class="input-group">
         <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
        <input type="text" name="search_date_to" id="date_to" class="form-control">
     </div>
</div>
<div style="margin-top:5px; ">
    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
         <button type="submit" class="btn btn-primary"><i class="fa fa-stop"></i> Clear</button>
</div>
</form>
</div>
popover;
            $action_buttons.=<<<ACTIONS
                      <span class='dropdown'> <a href="#" id="filter_popover" data-toggle="popover" data-trigger="click" data-placement="bottom" data-container="body" data-html="true" data-content='$html'>
                            <i class="fa fa-filter" aria-hidden="true"></i> Filter
                        </a></span>
                	
ACTIONS;

            return $action_buttons;
        }
    }
    public function receivePayment(Request $request){
        $data  = $request->json()->all();
        try{
            $mobileNumber = $data['source'];
            if(isset($data['requestMetadata']) && isset($data['requestMetadata']['mobile_number'])){
                $mobileNumber = $data['requestMetadata']['mobile_number'];
            }
            $values = explode(' ',$data['value']);
            if(isset($data['providerFee'])){
                $providerFees = $data['providerFee'];
            }
            if(isset($data['transactionFee'])){
                $providerFees = $data['transactionFee'];
            }
            $providerFees = explode(' ', $providerFees);
            if(isset($data['clientAccount']) && strlen($data['clientAccount'])){
                $mobileNumber = $data['clientAccount'];
                $prefix = mb_substr($mobileNumber, 0, 1);
                echo $mobileNumber.' ';
                if($prefix=='0'){
                    $mobileNumber = str_replace($prefix, '254',$mobileNumber);
                }
                if($prefix=='+'){
                    $mobileNumber = str_replace($prefix, '',$mobileNumber);
                }
               
            }
            //check if we already have an existing payment
            $oldPayment = Payment::where('reference', $data['transactionId'])->first();
            $mobileNumber = str_replace('+','',$mobileNumber);
            if(!$oldPayment){
                $payment = new Payment();
            }else{
                $payment = $oldPayment;
            }
            $type =($data['category']=='MobileCheckout' || $data['category']=='MobileC2B')?'credit':'debit';
            $payment->currency=$values[0];
            $payment->amount=$values[1];
            $payment->reference=$data['transactionId'];
            $payment->status=$data['status'];
             $payment->response=json_encode($data);
            $payment->provider_reference=$data['providerRefId'];
            $payment->provider_fee = $providerFees[1];
            $payment->transaction_date = $data['transactionDate'];
            $payment->mobile_number = $mobileNumber;
            $payment->type = $type;
            $payment->save();
            $details = array('mobile_number'=> $mobileNumber,
                'amount'=>$payment->amount,
                'payment_id'=>$payment->id,
               );
            $response = array();
            //check if it's customer paying loan
            if(($data['category']=='MobileCheckout' || $data['category']=='MobileC2B') && $data['status']=='Success'){
                $request->request->add(['action' => 'RepayLoan','request'=>json_encode($details)]);
                $serviceProcessor = new ServiceProcessor();
                $response = $serviceProcessor->doProcess($request);
            }else{
                //this is B2C notification
                Loan::where('transaction_ref', $payment->reference)->update(array('payment_status'=>$data['status']));
            }
        }catch(\Exception $e){
            Log::error('Error: '.$e->getMessage());
            Log::error("Response: ".json_encode($data));
            return array('Error: '.$e->getMessage());
        }
        return $response;
    }
    
   
}

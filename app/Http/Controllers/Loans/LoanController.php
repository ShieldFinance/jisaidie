<?php

namespace App\Http\Controllers\Loans;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProcessor;
use App\Http\Models\Loan;
use App\Http\Models\Customer;
use App\Http\Models\Message;
use App\Http\Models\ResponseTemplate;
use \App\Http\Models\Transaction;
use App\Http\Models\Organization;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Helpers\RepaymentsImport;
use App\Http\Models\Payment;
class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $organization_id = $request->get('search_organization');
        $type = $request->get('search_type');
        $status = $request->get('search_status');
        $search_date_from = $request->get('search_date_from');
        $search_date_to = $request->get('search_date_to');
        $perPage = 25;
        $action_buttons = $this->getActionButtons();
        $organizations = Organization::all();
        $wheres = array();
        $invoice_organization = $request->get('invoice_organization');
        $downloadSample = $request->get('download_sample');
        if($downloadSample){
            $this->downloadServiceSample();
        }
        if($invoice_organization){
            $this->printInvoice($invoice_organization);
        }
        
        if(!empty($organization_id)){
            $wheres[] =  ['organization.id' ,'=',$organization_id];        
        }
        if(!empty($keyword)){
            $wheres[] =  ['c.mobile_number' ,'LIKE',"%$keyword%"];
        }
        if(!empty($type)){
            $wheres[] =  ['loans.type','=',$type];
        }
        if(!empty($status)){
            $wheres[] =  ['loans.status','=',$status];
        }
        if(!empty($search_date_from)){
            $time = strtotime($search_date_from);
            $timeFrom = date('Y-m-d H:i:s',$time);
            if(!empty($search_date_to)){
                $time = strtotime($search_date_to);
                $timeTo = date('Y-m-d H:i:s',$time);
                $wheres[]=['loans.date_disbursed','>=',$timeFrom];
                $wheres[]=['loans.date_disbursed','<=', $timeTo];
            }else{
                Session::flash('flash_message','You must specify both start and end date');
            }
        }
        if(empty($wheres)){
            $wheres[] = ['loans.id','>',0];
        }
        
        $loans = DB::table('loans')
        ->join('customers as c', 'c.id', '=', 'loans.customer_id')
        ->leftJoin('organization', 'organization.id', '=', 'c.organization_id')
        ->where($wheres)
        ->select('loans.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
        ->orderBy('loans.id','desc')
        ->paginate($perPage);
        $request->session()->put('loans', $loans);
        return view('admin/loans.loan.index', compact('loans','action_buttons','organizations'));
    }
    
    public function processLoan(Request $request){
        $action_buttons = $this->getActionButtons();
        $action = $request->input('service');
        $serviceProcessor = new ServiceProcessor();
        $flashMessage = "";
        $perPage = 25;
        $user = Auth::user();
        $userIsAdmin =  Auth::user()->hasRole('Super Admin');
        if($action){
            $loan_ids = $request->input('loans');
            $loan_ids = explode(',', $loan_ids);
            foreach($loan_ids as $key=>$loan_id){
                if($loan_id=='on'){
                    unset($loan_ids[$key]);
                }
            }
            $details = array('loan_id'=>$loan_ids);
            $canProcess = false;
            
            if($action=='ApproveLoan' && $user->can('can_approve_loan')){
                $canProcess = true;
                $returnKey = 'approve_loan_application';
                $successMessage = "Loans approved";
                $checkStatus = config('app.responseCodes')['loan_approved'];
               
            }
            if($action=='DisburseLoan' && $user->can('can_disburse_loan')){
                $canProcess = true;
                $returnKey = 'send_funds';
                $successMessage = "Loans disbursed";
                $checkStatus = config('app.responseCodes')['loan_disbursed'];
                $details['send_loan'] = true;
                //send several loans, this need to be refactored in future to allow queuing for faster processing
                $request->request->add(['action' => $action,'request'=>json_encode($details)]);
                $response = $serviceProcessor->doProcess($request);
                $processedLoans = 0;
                foreach($loan_ids as $loan_id){
                    if(isset($response[$returnKey]) && $response[$returnKey]['response_status']==$checkStatus){
                        $processedLoans++;
                    }
                }
                if($processedLoans > 0){
                    Session::flash('flash_message', 'Loans sent');
                }else{
                    Session::flash('flash_message', 'Loans not sent');
                }
                 $loans = DB::table('loans')
                ->join('customers as c', 'c.id', '=', 'loans.customer_id')
                ->join('organization', 'organization.id', '=', 'c.organization_id')
                ->where([['loans.id','>',0]])
                ->select('loans.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
                ->orderBy('id','desc')
                ->paginate($perPage);
                 $request->session()->put('loans', $loans);
                 return view('admin/loans.loan.index', compact('loans','action_buttons'));
            }
            if($action=='RejectLoanApplication' && $user->can('can_reject_loan')){
                $canProcess = true;
                $returnKey = 'reject_loan_application';
                $successMessage = "Loans rejected";
                $checkStatus = config('app.responseCodes')['loan_rejected'];
            }
            if($action=='ReverseDisburseLoan' && $user->can('can_reverse_disbursal')){
                $canProcess = true;
                $returnKey = 'reverse_loan_disbursal';
                $successMessage = "Loans reversed";
                $checkStatus = config('app.responseCodes')['loan_disbursed_reversed'];
            }
            if($action=='PrintInvoice' && $user->can('can_invoice')){
                $canProcess = false;
                $this->printInvoice($request);
            }
            if($action=='ExportLoans' && $user->can('can_export_loans')) {
                $canProcess = false;
                $this->export($request);
            }
            
            if($canProcess){
                $request->request->add(['action' => $action,'request'=>json_encode($details)]);
                $response = $serviceProcessor->doProcess($request);
               
                if(isset($response[$returnKey]) && $response[$returnKey]['response_status']==$checkStatus){
                    $flashMessage = $successMessage;
                }else{
                    $flashMessage =isset($response[$returnKey])?$response[$returnKey]['response_string']:'Request failed';
                }
            }else{
               
                $flashMessage = "You do not have access to perform this action";
            }
        }
        $loans = DB::table('loans')
                ->join('customers as c', 'c.id', '=', 'loans.customer_id')
                ->leftjoin('organization', 'organization.id', '=', 'c.organization_id')
                ->where([['loans.id','>',0]])
                ->select('loans.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
                ->orderBy('loans.id','desc')
                ->paginate($perPage);
        if(strlen($flashMessage)){
            Session::flash('flash_message', $flashMessage);
        }
        $request->session()->put('loans', $loans);
        return view('admin/loans.loan.index', compact('loans','action_buttons'));
    }
    
    public function export(Request $request){
        Excel::create('Loans-'.date('Y-m-d'), function($excel) use($request) {
        $loans = $request->session()->get('loans');
        $data = array();
        $headers = array(
            'Mobile Number',
            'Amount Requested',
            'Amount Processed',
            'Daily Interest',
            'Fees',
            'Total',
            'Transaction Ref',
            'Paid',
            'Invoiced',
            'Status',
            'Date disbursed',
            'Deleted',
            'Date Created',
            'Date Updated',
            'Purpose',
            'Payment Status',
            'Type',
            'Transaction Fee',
            'Provider',
            'Net Salary'
        );
        $data[]=$headers;
        foreach($loans as $loan){
            $l = (array)$loan;
            unset($l['payment_response']);
            unset($l['id']);
            $l['status'] = array_search ($l['status'], config('app.loanStatus'));
            $l['customer_id'] = $loan->mobile_number;
            $data[] = $l;
        }
        $excel->sheet('Loan', function($sheet) use ($data) {

               $sheet->fromArray($data,null,'A1',false,false);

            });

        })->download('xls');
    }
    public function downloadServiceSample(){
        Excel::create('sample-'.date('Y-m-d'), function($excel) {
        $data = array();
        $headers = array(
            'id number',
            'name',
            'amount'
        );
        $data[]=$headers;
        $data[]=['11111111','John Doe','1000'];
        $data[]=['22222222','Jane Doe','1500'];
        $excel->sheet('Sheet1', function($sheet) use ($data) {

               $sheet->fromArray($data,null,'A1',false,false);

            });

        })->download('xlsx');
    }
    public function serviceLoans(Request $request){
        $user = Auth::user();
        $userIsAdmin =  Auth::user()->hasRole('Super Admin');
        if($user->can('service_loans') || $userIsAdmin){
            $serviceTye = $request->input('service_type');
            if($serviceTye=='service_selected'){
                $loan_ids = $request->input('loans');
                $loan_ids = explode(',', $loan_ids);
                if(!empty($loan_ids) && $loan_ids[0]!=''){
                    foreach($loan_ids as $key=>$loan_id){
                        if($loan_id=='on'){
                            unset($loan_ids[$key]);
                        }
                    }
                    Loan::whereIn('id',$loan_ids)->update(array('status'=>config('app.loanStatus')['paid'],'paid'=>DB::raw("`total`")));
                    Session::flash('flash_message', 'Loans serviced');
                    $rqst = array('action'=>'ServiceLoan','request'=>$loan_ids);
                    $transaction = new Transaction(['service_id'=>20,'request'=>json_encode($rqst),'status'=>'completed']);
                    $transaction->profile = $user->id;
                    $transaction->save();
                }else{
                    Session::flash('flash_message', 'No record selected');
                }
            }elseif($serviceTye=='service_document'){
                if ($request->hasFile('service_file')) {
                    $path = $request->service_file->store('documents');
                    $rqst = array('action'=>'ServiceLoan','request'=>$path);
                    $transaction = new Transaction(['service_id'=>20,'request'=>json_encode($rqst),'status'=>'completed']);
                    $transaction->profile = $user->id;
                    $transaction->save();
                    Excel::load(storage_path().'/app/'.$path, function($reader) {
                        // Getting all results
                        $results = $reader->get();  
                        $invalidAccounts = array();
                        $noActiveLoans = array();
                        $processed = 0;
                        foreach($results as $row){
                            $customer = Customer::where('id_number',$row->id_number)->first();
                             if($customer){
                                 
                                $loan = Loan::where([['customer_id','=',$customer->id],['status','=',config('app.loanStatus')['disbursed']]])->first();
                                
                                if($loan){
                                    $balance = (float)$loan->total - (float)$loan->paid;
                                    $amount = (float)$row->amount;
                                    $amountTodeduct = 0;
                                    if($balance > $amount){
                                        $loan->paid+=$amount;
                                        
                                    }else{
                                        //just deduct the balance and put the rest to witholding_balance
                                        $loan->paid+=$balance;
                                        $customer->withholding_balance+=$amount-$balance;
                                        $loan->status = config('app.loanStatus')['paid'];
                                        $customer->save();
                                    }
                                    $loan->save();
                                    $processed++;
                                }else{
                                    $noActiveLoans[]=$row->id_number;
                                }
                            }else{
                                $invalidAccounts[]=$row->id_number;
                            }
                        }
                        $message ='';
                        if($processed > 0){
                            $message.=$processed." loans serviced<br>";
                        }
                        if(!empty($invalidAccounts)){
                            $message .="The following accounts do not exist.<br><ol>";
                            foreach($invalidAccounts as $id_number){
                               $message.='<li>'.$id_number.'</li>'; 
                            }
                            $message.='</ol><br>';
                            
                        }
                        if(!empty($noActiveLoans)){
                            $message .="The following accounts have no active loans.<br><ol>";
                            foreach($noActiveLoans as $id_number){
                               $message.='<li>'.$id_number.'</li>'; 
                            }
                            $message.'</ol>';
                            
                        }
                        if(strlen($message)){
                            Session::flash('flash_message', $message);
                        }
                    });
                }else{
                    Session::flash('flash_message', 'Please select a valid excel file');
                }
            }
            
        }else{
            Session::flash('flash_message', 'You do not have permission to perform this action');
        }
        return redirect('admin/loan');
    }
    
    public function printInvoice($organizationId){
        $user = Auth::user();
        if($user->can('can_invoice')){
        $organization = \App\Http\Models\Organization::find($organizationId);
        $loans = DB::table('loans')
                ->join('customers as c', 'c.id', '=', 'loans.customer_id')
                ->leftjoin('organization', 'organization.id', '=', 'c.organization_id')
                ->where([['organization.id','=',$organizationId],['loans.status','=',config('app.loanStatus')['disbursed']]])
                ->select('loans.*','organization.name as organization_name','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
                ->orderBy('loans.id','desc')
                ->get();
        if(count($loans)){
            Excel::create($this->removeWhiteSpace($organization->name,'-').'-invoice-'.date('Y-m-d'), function($excel) use($loans,$organization) {
            $data = array();
            $headers = array(
                'Mobile Number',
                'Name',
                'Id Number',
                'Organization',
                'Total',
                'Status',
                'Date disbursed',
            );
            $data[]=$headers;
            $loan_ids = array();
            foreach($loans as $loan){
                $l = array();
                $l['mobile_number'] = $loan->mobile_number;
                $l['name'] = $loan->customer_name;
                $l['id_number'] = $loan->id_number;
                $l['organization'] = $loan->organization_name;
                $l['total'] = $loan->total;
                $l['status'] = array_search ($loan->status, config('app.loanStatus'));
                $l['date_disbursed'] = $loan->date_disbursed;
                $data[] = $l;
                $loan_ids[] = $loan->id;
            }
             
            $updated = Loan::whereIn('id', $loan_ids)->update(array('invoiced' => 1));
            
            $excel->sheet('Loan', function($sheet) use ($data) {

                   $sheet->fromArray($data,null,'A1',false,false);

                });

            })->download('xls');
        }else{
            Session::flash('flash_message', 'No loans to invoice for this organization');
        }
        }else{
            Session::flash('flash_message', 'You do not have permission to perform this action');
        }
    }
    
    function removeWhiteSpace($str, $sep='-')
    {
            $res = strtolower($str);
            $res = preg_replace('/[^[:alnum:]]/', ' ', $res);
            $res = preg_replace('/[[:space:]]+/', $sep, $res);
            return trim($res, $sep);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        if(Auth::user()->hasRole('Viewer')){
            Session::flash('flash_message', 'You do not have access to this resource');
            return redirect('admin');
        }
        return view('admin/loans.loan.create');
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
			'customer_id' => 'required',
			'amount_requested' => 'required',
			'daily_interest' => 'required',
			'total' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        Loan::create($requestData);

        Session::flash('flash_message', 'Loan added!');

        return redirect('admin/loan');
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
        $loan = Loan::findOrFail($id);

        return view('admin/loans.loan.show', compact('loan'));
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
        if(Auth::user()->hasRole('Viewer')){
            Session::flash('flash_message', 'You do not have access to this resource');
            return redirect('admin');
        }
        $loan = Loan::findOrFail($id);

        return view('admin/loans.loan.edit', compact('loan'));
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
			'customer_id' => 'required',
			'amount_requested' => 'required',
			'daily_interest' => 'required',
			'total' => 'required',
			'status' => 'required'
		]);
        $requestData = $request->all();
        
        $loan = Loan::findOrFail($id);
        $loan->update($requestData);

        Session::flash('flash_message', 'Loan updated!');

        return redirect('admin/loan');
    }
    
    public function getActionButtons(){
        $user = Auth::user();
        $action_buttons ='';
        if($user){
        $userIsAdmin =  Auth::user()->hasRole('Super Admin');
        
            if($user->can('can_approve_loan') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                        <a data-alert="Are you sure you want to approve selected loans?" data-form="loans_form" href="javascript:void(0)" data-service='ApproveLoan' class="btn btn-success btn-sm process_loan" title="Approve selected loans">
                                <i class="fa fa-check" aria-hidden="true"></i> Approve
                            </a>
ACTIONS;
            }
            
            if($user->can('can_reject_loan') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                       <a data-alert="Are you sure you want to reject selected loans?" data-form="loans_form" href="javascript:void(0)" data-service='RejectLoanApplication' class="btn btn-danger btn-sm process_loan" title="Reject selected loans">
                            <i class="fa fa-close" aria-hidden="true"></i> Reject
                        </a>
ACTIONS;
            }
            
            if($user->can('can_disburse_loan') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                       <a data-alert="Are you sure you want to disburse selected loans?" data-form="loans_form" href="javascript:void(0)" data-service='DisburseLoan' class="btn btn-primary btn-sm process_loan" title="Send loan to client">
                            <i class="fa fa-send" aria-hidden="true"></i> Disburse
                        </a>
ACTIONS;
            }
            
            if($user->can('can_reverse_disbursal') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                       <a data-alert="Are you sure you want to reverse selected loans?" data-form="loans_form" href="javascript:void(0)" data-service='ReverseDisburseLoan' class="btn btn-warning btn-sm process_loan" title="Change loan to approved status">
                            <i class="fa fa-undo" aria-hidden="true"></i> Reverse to approved
                        </a>
ACTIONS;
            }
            
            if($user->can('can_invoice') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                      <a href="javascript:void(0)" rel="popover"  data-popover-content="#invoicePopover" class="btn btn-info btn-sm" title="Invoice">
                            <i class="fa fa-download" aria-hidden="true"></i> Invoice
                        </a>
ACTIONS;
            }
            if($user->can('can_service_loan') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                      <a href="javascript:void(0)" rel="popover"  data-popover-content="#serviceLoan" class="btn btn-info btn-sm " title="Service Loans">
                            <i class="fa fa-check" aria-hidden="true"></i> Service Loan
                        </a>
ACTIONS;
            }
            if($user->can('can_export_loans') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                      <a data-form="loans_form" href="javascript:void(0)" data-service='ExportLoans'  class="btn btn-info btn-sm process_loan" title="Export Loans">
                            <i class="fa fa-download" aria-hidden="true"></i> Export to excel
                        </a>
ACTIONS;
            }
        }
$organizations = Organization::all();
$select = '<select class="form-control" name="search_organization">
         <option value="">Select</option>';
         if(isset($organizations)){
          foreach($organizations as $organization){
            $select.=' <option value="'.$organization->id.'">'.$organization->name.'</option> ';
          }
         } 
    $select.="</select>";
$status='<option value="'.config('app.loanStatus')['pending'].'">Pending</option>
         <option value="'.config('app.loanStatus')['approved'].'">Approved</option>
         <option value="'.config('app.loanStatus')['disbursed'].'">Disbursed</option>
         <option value="'.config('app.loanStatus')['rejected'].'">Rejected</option>
          <option value="'.config('app.loanStatus')['locked'].'">Locked</option>
          <option value="'.config('app.loanStatus')['paid'].'">Paid</option>';
$html = <<<popover
        <div id="popover-content" >
<form method="GET" action="/admin/loan" id="search_form" class="search_form" role="search">
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Search...">
        
    </div>
 <div class="input-group">
      <label for="">Organization</label>
        $select
    </div>
<div class="input-group">
    <label for="">Loan type</label>
     <select class="form-control" name="search_type">
         <option value="">Select</option>
         <option value="co">Checkoff</option>
         <option value="nco">Non Checkoff</option>
     </select>
    </div>
<div class="input-group">
    <label for="">Loan Status</label>
     <select class="form-control" name="search_status">
         <option value="">Select</option>
         $status
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        Loan::destroy($id);

        Session::flash('flash_message', 'Loan deleted!');

        return redirect('admin/loan');
    }
    public function fetchLoansJson(Request $request){
        $q = $request->get('q');
        $response = [];
        /*$loans = DB::table('loans')
        ->join('customers as c', 'c.id', '=', 'loans.customer_id')
        ->leftJoin('organization', 'organization.id', '=', 'c.organization_id')
        
        ->select('loans.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
       ->whereIn('c.id',DB::raw("select id from customers where mobile_number LIKE %".$query."%")->get()->toArray())
                ->orderBy('loans.id','desc')->get();*/
        $loans = DB::table('loans')
            ->leftJoin('customers as c', 'c.id', '=', 'loans.customer_id')
            ->select('loans.*','c.mobile_number','c.email','c.id_number',DB::raw('CONCAT(c.surname, " ", c.last_name) AS customer_name'))
            ->whereIn('loans.customer_id', function($query) use($q)
            {
                $query->select(DB::raw('id'))
                      ->from('customers')
                      ->whereRaw("customers.mobile_number LIKE '%".$q."%'");
            })
            ->get();
        if($loans->count()){
            foreach($loans as $loan){
                $balance = $loan->total - $loan->paid;
                if($balance <= 0)
                    continue;
                $response[]=[
                    'id'=>$loan->id,
                    'value'=>$loan->customer_name.'('.$loan->mobile_number.')'
                ];
            }
        }
        return response()->json($response);
    }
    public function reconcileLoan(Request $request){
        $loan_id = $request->input('loan_id');
        $payment_id = $request->input('payment_id');
        $loan = Loan::find($loan_id);
        $payment = Payment::find($payment_id);
        $message = '';
        if($loan && $payment && $payment->type=='credit'){
            $customer = Customer::find($loan->customer_id);
            $balance = $loan->total-$loan->paid;
            $payment->loan_id = $loan->id;
            $payment->mobile_number=$customer->mobile_number;
            if($balance > 0 && $balance >= $payment->amount){
                $loan->paid+=$payment->amount;
            }else if($balance > 0 && $payment->amount > $balance){
                $overPayment = $payment->amount - $balance;
                $loan->paid+=$balance;
                $customer->withholding_balance += $overPayment;
                $customer->save();
            }else if($balance <= 0){
                $message = 'Customer has no loan';
            }
            $effective_balance = $loan->total - $loan->paid;
            if($effective_balance <=0){
                $loan->status= config('app.loanStatus')['paid'];
            }
            $loan->save();
            $payment->save();
            $message = 'Payment reconciled';
        }elseif(!$loan){
           $message = 'No loan found'; 
        }elseif(!$payment){
            $message = 'No payment found';
        }elseif($payment->type=='debit'){
            $message = "This payment is a debit";
        }
        
        Session::flash('flash_message',$message);
        return redirect('admin/payments');
    }
    public function sendReminders(Request $request){
        $today = Carbon::today();  
        $daysAgo = $today->copy()->subDays(28)->toDateTimeString();
        $loans = Loan::whereDate('date_disbursed', '<=', $daysAgo)
	   ->whereDate('last_sent', '<>', date('Y-m-d'))
	   ->where('status','=',config('app.loanStatus')['disbursed'])
           ->take(200)
           ->get();
        $overDueMessage1 = ResponseTemplate::find(10);
        $overDueMessage2 = ResponseTemplate::find(10);;
        $reminderMessage1 = ResponseTemplate::find(11);;
        $reminderMessage2 = ResponseTemplate::find(12);;
        $messages = array();
        $messageString ='';
        $subject ='';
        $loan_ids=array();
        foreach($loans as $loan){
            $dateDisbursed = new Carbon($loan->date_disbursed);
            $dueDate = $dateDisbursed->copy()->addDays(30);
            $diff = $today->diffInDays($dueDate,false);
            echo 'Date disbursed: '.$dateDisbursed->toDateString().' <br>';
            echo 'Due date: '.$dueDate->toDateString().' <br>';
            echo 'Day remaining: '.$diff.'<p>';
            $balance = $loan->total - $loan->paid;
            if($diff==2){
                $messageString = $reminderMessage1->message;
                $subject = $reminderMessage1->subject;
            }elseif($diff==0){
                $messageString = $reminderMessage2->message;
                $subject = $reminderMessage2->subject;
            }elseif($diff == -1){
                $messageString = $overDueMessage1->message;
                $subject = $overDueMessage1->subject;
            }elseif($diff == -5){
                $messageString = $overDueMessage2->message;
                $subject = $overDueMessage2->subject;
            }
            if(strlen($messageString)){
                $loan_ids[] = $loan->id;
                $messageString = str_replace('[customer_name]', $loan->customer->surname, $messageString);
                $messageString = str_replace('[amount]', $loan->total, $messageString);
                $messageString = str_replace('[loan_balance]', $balance, $messageString);
                $messageString = str_replace('[due_date]', $dueDate->format('F d, Y'), $messageString);
                $messages[] = [
                            'subject' => $subject,
                            'message' => $messageString,
                            'recipient' => $loan->customer->mobile_number,
                            'type' => 'sms',
                            'status' => 'pending',
                            'attempts' => 0,
                            'service_id'=>0
                    ];
            }
        }
        if(!empty($messages)){
            Message::insert($messages);
            Loan::whereIn('id', $loan_ids)->update(['last_sent'=>$today->toDateTimeString()]);
        }
        
    }
    
    public function lockLoans(Request $request){
        $today = Carbon::today();  
        $daysAgo = $today->subDays(60)->toDateTimeString();
        $loans = Loan::whereDate('date_disbursed', '<=', $daysAgo)
	   ->whereDate('last_sent', '<>', date('Y-m-d'))
	   ->where('status','=',config('app.loanStatus')['disbursed'])
           ->take(200)
           ->get();
        if(!empty($loans)){
            $loan_ids = array();
            $customer_ids = array();
            $messages = array();
            $template = ResponseTemplate::find(13);
            $messageString = $template->message;
            foreach($loans as $loan){
                $balance = $loan->total - $loan->paid;
                if($balance > 0){
                    $loan_ids[]=$loan->id;
                    $customer_ids[]=$loan->customer_id;
                    $dateDisbursed = new Carbon($loan->date_disbursed);
                    $dueDate = $dateDisbursed->copy()->addDays(30);
                    $messageString = str_replace('[customer_name]', $loan->customer->surname, $messageString);
                    $messageString = str_replace('[loan_balance]', $balance, $messageString);
                    $messageString = str_replace('[due_date]', $dueDate->format('F d, Y'), $messageString);
                     $messages[] = [
                            'subject' => $template->subject,
                            'message' => $messageString,
                            'recipient' => $loan->customer->mobile_number,
                            'type' => 'sms',
                            'status' => 'pending',
                            'attempts' => 0,
                            'service_id'=>0
                    ];
                }
            }
            
            if(!empty($messages)){
                Customer::whereIn('id', $customer_ids)->update(array('status' => config('app.customerStatus')['locked']));
                Loan::whereIn('id', $loan_ids)->update(array('status' => config('app.loanStatus')['locked']));
                Message::insert($messages);
            }
        }
    }
    
    public function applyDailyCharges(Request $request){
        $app = \App::getFacadeRoot();
        $loanService = $app->make('Loan');
        $loans = Loan::where('status','=',config('app.loanStatus')['disbursed'])
                       ->where('last_fees_update','<>',date('Y-m-d'))
                       ->take(200)
                       ->get();
        if(!empty($loans)){
            foreach($loans as $loan){
                $balance = $loan->total - $loan->paid;
                $loanService->applyCharges($loan);
            }
        }
    }
}

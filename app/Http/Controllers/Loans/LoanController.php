<?php

namespace App\Http\Controllers\Loans;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProcessor;
use App\Http\Models\Loan;
use App\Http\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Session;
use Illuminate\Support\Facades\DB;

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
        $perPage = 25;
        $action_buttons = $this->getActionButtons();
        $organizations = \App\Http\Models\Organization::all();
        $wheres = array();
        
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
        if(empty($wheres)){
            $wheres[] = ['loans.id','>',0];
        }
        $loans = DB::table('loans')
        ->join('customers as c', 'c.id', '=', 'loans.customer_id')
        ->join('organization', 'organization.id', '=', 'c.organization_id')
        ->where($wheres)
        ->select('loans.*','c.mobile_number')
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
                ->where(['id','>',0])
                ->select('loans.*','c.mobile_number')
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
            if($action=='ExportLoans' && $user->can('can_export_loans')) {
                $canProcess = false;
                $this->export($request);
            }
            if($userIsAdmin){
                $canProcess = true;
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
                ->join('organization', 'organization.id', '=', 'c.organization_id')
                ->where([['loans.id','>',0]])
                ->select('loans.*','c.mobile_number')
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
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
                        <a href="javascript:void(0)" data-service='ApproveLoan' class="btn btn-success btn-sm process_loan" title="Approve selected loans">
                                <i class="fa fa-check" aria-hidden="true"></i> Approve
                            </a>
ACTIONS;
            }
            
            if($user->can('can_reject_loan') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                       <a href="javascript:void(0)" data-service='RejectLoanApplication' class="btn btn-danger btn-sm process_loan" title="Reject selected loans">
                            <i class="fa fa-close" aria-hidden="true"></i> Reject
                        </a>
ACTIONS;
            }
            
            if($user->can('can_disburse_loan') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                       <a href="javascript:void(0)" data-service='DisburseLoan' class="btn btn-primary btn-sm process_loan" title="Send loan to client">
                            <i class="fa fa-send" aria-hidden="true"></i> Disburse
                        </a>
ACTIONS;
            }
            
            if($user->can('can_reverse_disbursal') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                       <a href="javascript:void(0)" data-service='ReverseDisburseLoan' class="btn btn-warning btn-sm process_loan" title="Change loan to approved status">
                            <i class="fa fa-undo" aria-hidden="true"></i> Reverse to approved
                        </a>
ACTIONS;
            }
            
            if($user->can('can_export_loans') || $userIsAdmin) {
                $action_buttons.=<<<ACTIONS
                      <a href="javascript:void(0)" data-service='ExportLoans' class="btn btn-info btn-sm process_loan" title="Export Loans">
                            <i class="fa fa-download" aria-hidden="true"></i> Export to excel
                        </a>
ACTIONS;
            }
        }
        $action_buttons.=<<<ACTIONS
                      <span class='dropdown'> <a href="#" rel="popover" data-popover-content="#myPopover">
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
}

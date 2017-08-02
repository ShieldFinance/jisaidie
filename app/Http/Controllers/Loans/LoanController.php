<?php

namespace App\Http\Controllers\Loans;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProcessor;
use App\Http\Models\Loan;
use Illuminate\Http\Request;
use Session;

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
        $perPage = 25;
        $action_buttons = $this->getActionButtons();
        if (!empty($keyword)) {
            $loan = Loan::where('customer_id', 'LIKE', "%$keyword%")
				->orWhere('amount_requested', 'LIKE', "%$keyword%")
				->orWhere('amount_processed', 'LIKE', "%$keyword%")
				->orWhere('daily_interest', 'LIKE', "%$keyword%")
				->orWhere('fees', 'LIKE', "%$keyword%")
				->orWhere('total', 'LIKE', "%$keyword%")
				->orWhere('transaction_ref', 'LIKE', "%$keyword%")
				->orWhere('paid', 'LIKE', "%$keyword%")
				->orWhere('invoiced', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->orWhere('net_salary', 'LIKE', "%$keyword%")
				->orWhere('date_disbursed', 'LIKE', "%$keyword%")
				->orWhere('deleted', 'LIKE', "%$keyword%")
                                ->orderBy('id','desc')
				->paginate($perPage);
            
        } else {
            $loan = Loan::orderBy('id','desc')->paginate($perPage);
        }

        return view('admin/loans.loan.index', compact('loan','action_buttons'));
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
                $returnKey = 'disburse_loan';
                $successMessage = "Loans disbursed";
                $checkStatus = config('app.responseCodes')['loan_disbursed'];
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
                $canProcess = true;
            }
            if($userIsAdmin){
                $canProcess = true;
            }
            if($canProcess){
                $request->request->add(['action' => $action,'request'=>json_encode($details)]);
                $response = $serviceProcessor->doProcess($request);
                if($response[$returnKey]['response_status']==$checkStatus){
                    $flashMessage = $successMessage;
                }else{
                    $flashMessage =$response[$returnKey]['response_string'];
                }
            }else{
               
                $flashMessage = "You do not have access to perform this action";
            }
        }
        $loan = Loan::orderBy('id','desc')->paginate($perPage);
        if(strlen($flashMessage)){
            Session::flash('flash_message', $flashMessage);
        }
        return view('admin/loans.loan.index', compact('loan','action_buttons'));
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
                      <a href="javascript:void(0)" data-act='export_loan' class="btn btn-info btn-sm process_loan" title="Export Loans">
                            <i class="fa fa-download" aria-hidden="true"></i> Export to excel
                        </a>
ACTIONS;
            }
        }
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

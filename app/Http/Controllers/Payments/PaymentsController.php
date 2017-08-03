<?php

namespace App\Http\Controllers\Payments;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ServiceProcessor;
use App\Http\Models\Payment;
use App\Http\Models\Loan;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Log;

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

        if (!empty($keyword)) {
            $payments = Payment::where('amount', 'LIKE', "%$keyword%")
				->orWhere('curreny', 'LIKE', "%$keyword%")
				->orWhere('reference', 'LIKE', "%$keyword%")
				->orWhere('gateway', 'LIKE', "%$keyword%")
				->orWhere('loan_id', 'LIKE', "%$keyword%")
                                ->orderBy('id','desc')
				->paginate($perPage);
        } else {
            $payments = Payment::orderBy('id','desc')->paginate($perPage);
        }

        return view('payments.payments.index', compact('payments'));
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
    
    public function receivePayment(Request $request){
        $data  = $request->json()->all();
        try{
            $mobileNumber = $data['source'];
            if(isset($data['requestMetadata'])){
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
            return array('Error: '.$e->getMessage());
        }
        return $response;
    }
    
   
}

<?php

namespace App\Http\Controllers\Services;
use Illuminate\Support\Facades\Log;
use App\Http\Requests;
use App\Jobs\SendBatchMessages;
use App\Http\Controllers\Controller;
use App\Mail\AppEmail;
use App\Http\Models\Message;
use App\Http\Models\Customer;
use Illuminate\Http\Request;
use Session;

class MessagesController extends Controller
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
            $messages = Message::where('subject', 'LIKE', "%$keyword%")
				->orWhere('message', 'LIKE', "%$keyword%")
				->orWhere('recipient', 'LIKE', "%$keyword%")
				->orWhere('type', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->orWhere('attempts', 'LIKE', "%$keyword%")
				->orWhere('service_id', 'LIKE', "%$keyword%")
                                ->orderBy('id','desc')
				->paginate($perPage);
        } else {
            $messages = Message::orderBy('id','desc')->paginate($perPage);
        }

        return view('admin/messages.messages.index', compact('messages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $selectedCustomerIds = $request->input('customers');
        $customers = Customer::all();
        return view('admin/messages.messages.create',['customers'=>$customers]);
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
			'subject' => 'required',
			'message' => 'required',
			'recipient' => 'required',
			'type' => 'required',
			'status' => 'required',
			'attempts' => 'required'
		]);
        $requestData = $request->all();
        $recepients = $requestData['recipient'];
        $data = array();
        foreach($recepients as $recepient){
            $data[] = [
			'subject' => $requestData['subject'],
			'message' => $requestData['message'],
			'recipient' => $recepient,
			'type' => $requestData['type'],
			'status' => $requestData['status'],
			'attempts' => 0,
                        'service_id'=>0
		];
        }
        Message::insert($data);

        Session::flash('flash_message', 'Message added!');
        Log::info("Request Cycle with Queues Begins");
        $this->dispatch(new SendBatchMessages());
        Log::info("Request Cycle with Queues Ends");
        return redirect('admin/messages');
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
        $message = Message::findOrFail($id);

        return view('admin/messages.messages.show', compact('message'));
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
        $message = Message::findOrFail($id);
        $customers = Customer::all();
        return view('admin/messages.messages.edit', array('message'=>$message,'customers'=>$customers));
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
			'subject' => 'required',
			'message' => 'required',
			'recipient' => 'required',
			'type' => 'required',
			'status' => 'required',
			'attempts' => 'required'
		]);
        $requestData = $request->all();
        
        $message = Message::findOrFail($id);
        $message->update($requestData);

        Session::flash('flash_message', 'Message updated!');

        return redirect('admin/messages');
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
        Message::destroy($id);

        Session::flash('flash_message', 'Message deleted!');

        return redirect('admin/messages');
    }
    public function sendMessage(Request $request){
        $data = array();
        $selectedCustomers = $request->input('customers');
        $selectedCustomers = explode(',', $selectedCustomers);
        if($selectedCustomers[0]=='on')
            unset($selectedCustomers[0]);
        return view('admin/messages.messages.create',['selectedCustomers'=>$selectedCustomers]);
    }
    public function sendQueuedMessages()
    {
        $app = \App::getFacadeRoot();
        $messagingService = $app->make('Message');
        $messagingService->sendMessages();
    }
}

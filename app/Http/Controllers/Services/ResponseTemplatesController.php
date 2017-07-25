<?php

namespace App\Http\Controllers\Services;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\ResponseTemplate;
use App\Http\Models\Message;
use Illuminate\Http\Request;
use Session;

class ResponseTemplatesController extends Controller
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
            $responsetemplates = ResponseTemplate::where('name', 'LIKE', "%$keyword%")
				->orWhere('subject', 'LIKE', "%$keyword%")
				->orWhere('message', 'LIKE', "%$keyword%")
				->orWhere('type', 'LIKE', "%$keyword%")
				->orWhere('service', 'LIKE', "%$keyword%")
				->orWhere('description', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $responsetemplates = ResponseTemplate::paginate($perPage);
        }

        return view('admin.response-templates.index', compact('responsetemplates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.response-templates.create');
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
			'name' => 'required',
			'subject' => 'required',
			'message' => 'required',
			'type' => 'required',
			'service' => 'required',
			'description' => 'required'
		]);
        $requestData = $request->all();
        
        ResponseTemplate::create($requestData);

        Session::flash('flash_message', 'ResponseTemplate added!');

        return redirect('admin/response-templates');
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
        $responsetemplate = ResponseTemplate::findOrFail($id);

        return view('admin.response-templates.show', compact('responsetemplate'));
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
        $responsetemplate = ResponseTemplate::findOrFail($id);

        return view('admin.response-templates.edit', compact('responsetemplate'));
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
			'name' => 'required',
			'subject' => 'required',
			'message' => 'required',
			'type' => 'required',
			'service_id' => 'required',
			'description' => 'required'
		]);
        $requestData = $request->all();
        
        $responsetemplate = ResponseTemplate::findOrFail($id);
        $responsetemplate->update($requestData);

        Session::flash('flash_message', 'ResponseTemplate updated!');

        return redirect('admin/response-templates');
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
        ResponseTemplate::destroy($id);

        Session::flash('flash_message', 'ResponseTemplate deleted!');

        return redirect('admin/response-templates');
    }
    
    public function processResponse($payload){
        $messageSent = false;
        if(isset($payload['service_id'])){
            $responseTemplates = ResponseTemplate::where(['service_id'=>$payload['service_id'], 'status'=>1])->get();
            if(count($responseTemplates)){
                $saved = 0;
                foreach($responseTemplates as $template){
                    $message = $template->message;
                    $subject = $template->subject;
                    $details = array();
                    $status = 'pending';
                    if(isset($payload['send_now']) and $payload['send_now']){
                        $status = 'Success';
                    }
                    if(!empty($payload['message_placeholders'])){
                        foreach($payload['message_placeholders'] as $key => $value){
                            $message = str_replace($key, $value, $message);
                        }
                    }
                    if(!empty($payload['subject_placeholders'])){
                        foreach($payload['subject_placeholders'] as $k => $v){
                            $subject = str_replace($k, $v, $subject);
                        }
                    }
                    
                    $recipient = $template->type=='email'?$payload['email']:$payload['msisdn'];
                    $messaging = new Message([
                        'subject'=>$subject,
                        'message'=>$message,
                        'recipient'=>$recipient,
                        'type'=>$template->type,
                        'status'=>$status,
                        'service_id'=>$payload['service_id'],
                        'attempts'=>0
                    ]);
                    if($messaging->save($details)){
                        $saved++;
                        if(isset($payload['send_now']) and $payload['send_now']){
                            $app = \App::getFacadeRoot();
                            $messagingService = $app->make('Message');
                            $messagingService->sendMessage(array('message_id'=>$messaging->id,'type'=>$messaging->type));
                        }
                    }
                    
                }
                if($saved){
                    
                    $messageSent = true;
                }
            }
        }
        return $messageSent;
    }
}

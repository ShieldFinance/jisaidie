<?php

namespace App\Http\Controllers\ServiceCommands;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\ServiceCommand;
use Illuminate\Http\Request;
use Session;

class ServiceCommandsController extends Controller
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
            $servicecommands = ServiceCommand::where('processing_function', 'LIKE', "%$keyword%")
				->orWhere('service_id', 'LIKE', "%$keyword%")
				->orWhere('level', 'LIKE', "%$keyword%")
				->orWhere('description', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $servicecommands = ServiceCommand::paginate($perPage);
        }

        return view('admin/servicecommands.service-commands.index', compact('servicecommands'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/servicecommands.service-commands.create');
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
			'processing_function' => 'required',
			'service_id' => 'required',
			'level' => 'required'
		]);
        $requestData = $request->all();
        
        ServiceCommand::create($requestData);

        Session::flash('flash_message', 'ServiceCommand added!');

        return redirect('admin/service-commands');
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
        $servicecommand = ServiceCommand::findOrFail($id);

        return view('admin/servicecommands.service-commands.show', compact('servicecommand'));
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
        $servicecommand = ServiceCommand::findOrFail($id);

        return view('admin/servicecommands.service-commands.edit', compact('servicecommand'));
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
			'processing_function' => 'required',
			'service_id' => 'required',
			'level' => 'required'
		]);
        $requestData = $request->all();
        
        $servicecommand = ServiceCommand::findOrFail($id);
        $servicecommand->update($requestData);

        Session::flash('flash_message', 'ServiceCommand updated!');

        return redirect('admin/service-commands');
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
        ServiceCommand::destroy($id);

        Session::flash('flash_message', 'ServiceCommand deleted!');

        return redirect('admin/service-commands');
    }
}

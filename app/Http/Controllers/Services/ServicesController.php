<?php

namespace App\Http\Controllers\Services;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Service;
use Illuminate\Http\Request;
use Session;

class ServicesController extends Controller
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
            $services = Service::where('name', 'LIKE', "%$keyword%")
				->orWhere('product', 'LIKE', "%$keyword%")
				->orWhere('description', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $services = Service::paginate($perPage);
        }

        return view('admin/services.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/services.services.create');
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
			'product' => 'required',
			'description' => 'required'
		]);
        $requestData = $request->all();
        
        Service::create($requestData);

        Session::flash('flash_message', 'Service added!');

        return redirect('admin/services');
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
        $service = Service::findOrFail($id);

        return view('admin/services.services.show', compact('service'));
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
        $service = Service::findOrFail($id);

        return view('admin/services.services.edit', compact('service'));
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
			'product' => 'required',
			'description' => 'required'
		]);
        $requestData = $request->all();
        
        $service = Service::findOrFail($id);
        $service->update($requestData);

        Session::flash('flash_message', 'Service updated!');

        return redirect('admin/services');
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
        Service::destroy($id);

        Session::flash('flash_message', 'Service deleted!');

        return redirect('admin/services');
    }
}

<?php

namespace App\Http\Controllers\CustomerDevice;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\CustomerDevice;
use Illuminate\Http\Request;
use Session;

class CustomerDeviceController extends Controller
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
            $customerdevice = CustomerDevice::where('device_id', 'LIKE', "%$keyword%")
				->orWhere('customer_id_number', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $customerdevice = CustomerDevice::paginate($perPage);
        }

        return view('admin/devices.customer-device.index', compact('customerdevice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin/devices.customer-device.create');
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
			'device_id' => 'required',
			'customer_id_number' => 'required'
		]);
        $requestData = $request->all();
        
        CustomerDevice::create($requestData);

        Session::flash('flash_message', 'CustomerDevice added!');

        return redirect('admin/customer-device');
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
        $customerdevice = CustomerDevice::findOrFail($id);

        return view('admin/devices.customer-device.show', compact('customerdevice'));
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
        $customerdevice = CustomerDevice::findOrFail($id);

        return view('admin/devices.customer-device.edit', compact('customerdevice'));
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
			'device_id' => 'required',
			'customer_id_number' => 'required'
		]);
        $requestData = $request->all();
        
        $customerdevice = CustomerDevice::findOrFail($id);
        $customerdevice->update($requestData);

        Session::flash('flash_message', 'CustomerDevice updated!');

        return redirect('admin/customer-device');
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
        CustomerDevice::destroy($id);

        Session::flash('flash_message', 'CustomerDevice deleted!');

        return redirect('admin/customer-device');
    }
}

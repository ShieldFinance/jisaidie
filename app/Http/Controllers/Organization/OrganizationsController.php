<?php

namespace App\Http\Controllers\Organization;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Http\Models\Organization;
use Illuminate\Http\Request;
use Session;

class OrganizationsController extends Controller
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
            $organizations = Organization::where('name', 'LIKE', "%$keyword%")
				->orWhere('email', 'LIKE', "%$keyword%")
				->orWhere('address', 'LIKE', "%$keyword%")
				->orWhere('mobile_number', 'LIKE', "%$keyword%")
				->orWhere('status', 'LIKE', "%$keyword%")
				->paginate($perPage);
        } else {
            $organizations = Organization::paginate($perPage);
        }

        return view('Organization.organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('Organization.organizations.create');
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
			'email' => 'required',
			'mobile_number' => 'required'
		]);
        $requestData = $request->all();
        
        Organization::create($requestData);

        Session::flash('flash_message', 'Organization added!');

        return redirect('Organization/organizations');
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
        $organization = Organization::findOrFail($id);

        return view('Organization.organizations.show', compact('organization'));
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
        $organization = Organization::findOrFail($id);

        return view('Organization.organizations.edit', compact('organization'));
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
			'email' => 'required',
			'mobile_number' => 'required'
		]);
        $requestData = $request->all();
        
        $organization = Organization::findOrFail($id);
        $organization->update($requestData);

        Session::flash('flash_message', 'Organization updated!');

        return redirect('Organization/organizations');
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
        Organization::destroy($id);

        Session::flash('flash_message', 'Organization deleted!');

        return redirect('Organization/organizations');
    }
}

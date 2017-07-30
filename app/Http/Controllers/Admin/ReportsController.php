<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Report;
use Illuminate\Http\Request;
use Session;
use Charts;
class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
       $activeUsers= Charts::create('pie', 'highcharts')
				->title('Active Users')
				->labels(['Active users', 'Inactive Users'])
				->values([30,70])
				->dimensions(500,250)
				->responsive(false);	
		
		 $verifiedUsers= Charts::create('pie', 'highcharts')
				->title('Verified Users')
				->labels(['Active users', 'Inactive Users'])
				->values([30,70])
				->dimensions(500,250)
				->responsive(false);	
		
		$check_off_summary= Charts::create('pie', 'highcharts')
				->title('Check off loans')
				->labels(['Disbursed', 'Pending Approval','Serviced','Defaulted'])
				->values([30,70,50])
				->dimensions(500,250)
				->responsive(false);	
			
			
        return view('admin.reports.index', ['activeUsers' => $activeUsers,'verifiedUsers' => $verifiedUsers,'check_off_summary' => $check_off_summary]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.reports.create');
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
        
        $requestData = $request->all();
        
        Report::create($requestData);

        Session::flash('flash_message', 'Report added!');

        return redirect('admin/reports');
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
        $report = Report::findOrFail($id);

        return view('admin.reports.show', compact('report'));
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
        $report = Report::findOrFail($id);

        return view('admin.reports.edit', compact('report'));
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
        
        $requestData = $request->all();
        
        $report = Report::findOrFail($id);
        $report->update($requestData);

        Session::flash('flash_message', 'Report updated!');

        return redirect('admin/reports');
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
        Report::destroy($id);

        Session::flash('flash_message', 'Report deleted!');

        return redirect('admin/reports');
    }
}

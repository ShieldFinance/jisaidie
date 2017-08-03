<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Models\Customer;
use App\Http\Models\Organization;
use App\Http\Models\Loan;
use App\Report;
use Illuminate\Http\Request;
use Session;
use Charts;
use Response;
use DB;
class ReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
     
		$customers = Customer::get();
		$Loans = Loan::get();
		$organizations = Organization::get();
		
		
		$years=array();
		
		$organizations_array=array();
		foreach(range(2015, date("Y")) as $year){
			$years[$year]=$year;
				
		}
		$loan_modes=array(''=>'-','co'=>'Check off','nco'=>'Non-Check off');
		foreach($organizations as $organization){
			$organizations_array[$organization->id]=$organization->name;
				
		}
		
		
		$customers_analytic_array=array();
		
		$customers_analytic_array['id_verified']=0;
		$customers_analytic_array['id_verified_no']=0;

		$customers_analytic_array['co_active_status']=0;
		$customers_analytic_array['nco_active_status']=0;
		
		$customers_analytic_array['co_active_status_no']=0;
		$customers_analytic_array['nco_active_status_no']=0;
		
		$customers_analytic_array['status']=0;
		$customers_analytic_array['status_no']=0;
		
		
		foreach($customers as $customer){
			if($customer->id_verified)
			$customers_analytic_array['id_verified']+=1;
			else
			$customers_analytic_array['id_verified_no']+=1;
			
			
			
			if($customer->status)
			$customers_analytic_array['status']+=1;
			else
			$customers_analytic_array['status_no']+=1;
			
			if($customer->status){
				if($customer->is_checkoff)
				$customers_analytic_array['co_active_status']+=1;
				else
				$customers_analytic_array['nco_active_status']+=1;
			}else{
				if($customer->is_checkoff)
				$customers_analytic_array['co_active_status_no']+=1;
				else
				$customers_analytic_array['nco_active_status_no']+=1;
			}
		}
		
		
		$active_inactive= Charts::create('pie', 'chartjs')
				->title(null)
				->labels(['Active users', 'Inactive Users'])
				->values([$customers_analytic_array['status'],$customers_analytic_array['status_no']])
				->dimensions(400,200)
				->responsive(false);	
		
		$co_nco_active= Charts::create('pie', 'chartjs')
				->title(null)
				->labels(['Check off', 'None Check off'])
				->values([$customers_analytic_array['co_active_status'],$customers_analytic_array['nco_active_status']])
				->dimensions(400,200)
				->responsive(false);	
				
		$co_nco_inactive= Charts::create('pie', 'chartjs')
				->title(null)
				->labels(['Check off', 'None Check off'])
				->values([$customers_analytic_array['co_active_status_no'],$customers_analytic_array['nco_active_status_no']])
				->dimensions(400,200)
				->responsive(false);	
				
		$verified_unverified_nco= Charts::create('pie', 'chartjs')
				->title(null)
				->labels(['Verified', 'Unverified'])
				->values([$customers_analytic_array['id_verified'],$customers_analytic_array['id_verified_no']])
				->dimensions(400,200)
				->responsive(false);	
		$repeatBorrowers= Charts::create('pie', 'chartjs')
				->title(null)
				->labels(['Repeat borrowers', 'Non Repeat Borrowers'])
				->values([30,70])
				->dimensions(400,200)
				->responsive(false);	
		
		
		
		$loans_analytics=array();
		$loans_analytics['pending']=0;
		$loans_analytics['rejected']=0;
		$loans_analytics['approved']=0;
		$loans_analytics['disbursed']=0;
		$loans_analytics['paid']=0;
		$loans_analytics['locked']=0;
		foreach($Loans as $loan){
			
			switch($loan->status){
				case 2:
					$loans_analytics['pending']+=$loan->total;
					break;
				case 3:
					$loans_analytics['rejected']+=$loan->total;
					break;
				case 4:
					$loans_analytics['approved']+=$loan->total;
					break;
				case 5:
					$loans_analytics['disbursed']+=$loan->total;
					break;
				case 6:
					$loans_analytics['paid']+=$loan->total;
					break;
				case 7:
					$loans_analytics['locked']+=$loan->total;
					break;
			}
			
		}
	   
        return view('admin.reports.index', [
											'years'=>$years,
											'active_inactive' => $active_inactive,
											'co_nco_active' => $co_nco_active,
											'co_nco_inactive' => $co_nco_inactive,
											'verified_unverified_nco' => $verified_unverified_nco,
											'repeatBorrowers' => $repeatBorrowers,
											'loans_analytics' => $loans_analytics,
											'organizations_array' => array_merge(array('0'=>"Organization"),$organizations_array),
											'loan_modes' => $loan_modes,
											
											]);
    }
    public function userRegistration(Request $request) {
		
		
		$months = array(
			'01'=>'January',
			'02'=>'February',
			'03'=>'March',
			'04'=>'April',
			'05'=>'May',
			'06'=>'June',
			'07'=>'July ',
			'08'=>'August',
			'09'=>'September',
			'10'=>'October',
			'11'=>'November',
			'12'=>'December',
		);
		
		$year = $request->input('year')?$request->input('year'): date("Y");
		$customers = Customer::get();
		
		
		$monthly_registration=array();
		foreach($months as $key=>$month){
			$monthly_registration[$key]=0;
			for($i=1;$i<=31;$i++){
				$monthly_registration_days[$key."-".sprintf("%02d",$i)]=0;
			}
			
			
		}
		
	    foreach($customers as $customer){//for each customer get created at date
		   
		   $created_at_year=date("Y",strtotime($customer->created_at));
	       $created_at_month=date("m",strtotime($customer->created_at));
		   $created_at_month_day=date("m-d",strtotime($customer->created_at));
		   if($created_at_year==$year){
			 $monthly_registration[$created_at_month]+=1;
			 $monthly_registration_days[$created_at_month_day]+=1;
		   }
		   
		}
	    
		
		
		
		$data=array();
		$drilldown =new \stdClass;
		foreach($monthly_registration as $m_key=>$m_value){
				
			
			$seriesData=new \stdClass;
			$seriesData->name=$months[$m_key];
			$seriesData->y=$m_value;
			$seriesData->drilldown="_".$m_key;
			
			$data[]=$seriesData;
			
			
	        
			$drilldownattributes=new \stdClass;
			$drilldownattributes->name=$months[$m_key];
			$drilldownattributes->id="_".$m_key;
			
			
			foreach($monthly_registration_days as $day_key=>$monthly_registration_day){
			  
			    $day=explode("-",$day_key);
				if($day[0]==$m_key){
					$drilldownattributes->data[]= [
						'Day '.$day[1],
						$monthly_registration_day
					];
				}
				
			}
			$drilldown->series[]=$drilldownattributes;
	  
		}
		
		
		
		
		$attributes=new \stdClass;
		$attributes->name="Month";
		$attributes->colorByPoint=false;
		
		
		
		$attributes->data=$data;
		
	    $series=array(
					  $attributes
					  );
	   
	   
	   
	   return Response::json(array('series' => $series,'drilldown'=>$drilldown));
	   
    }
	public function loanStats(Request $request) {
		$series=array();
		$company=$request->input('organization');//company from which to retrieve data
		$type=$request->input('type');//the type of loan data in view
		$year=$request->input('year')?$request->input('year'):date("Y");//year for viewing data
		
		$start_date=$year."-01-01 00:00:00";
		$end_date=$year."-12-31 11:59:59";
	      
//		'loanStatus' =>[
//        'pending' => 2,//New loan pending approval
//        'rejected' => 3,//rejected (for checkoff loans)
//        'approved'=>4,//approved (for checkoff loans)
//        'disbursed'=>5,//loan disbursed to customer,
//        'paid'=>6,//loan disbursed to customer
//        'locked'=>7,//loan disbursed to customer
//    ],

		 $declined=$approved=$pending=$disbursed=$serviced=array();
		 for($k=1;$k<13;$k++){
		   $declined[$k]=$pending[$k]=$disbursed[$k]=$approved[$k]=$serviced[$k]=$locked[$k]=0;
		 }
		 switch($type){
			case 2:
				$where=array();
				
				if($company){
				     $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->join('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*')
				->get();
				
				
				foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $pending[(int)$month]+=$record->total;
				}
				$series=$pending;
				break;
			case 3:
				$where=array();
				
				if($company){
				     $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*')
				->get();
				
				
				
				foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $declined[(int)$month]+=$record->total;
				}
				$series=$declined;
				break;
			case 4:
				$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*')
				->get();
				foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $approved[(int)$month]+=$record->total;
				}
			
				$series=$approved;
				break;
			case 5:
				$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*')
				->get();
				
				
				foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $disbursed[(int)$month]+=$record->total;
				}
				$series=$disbursed;
			case 6:
				$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*')
				->get();
				foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $serviced[(int)$month]+=$record->total;
				}
				$series=$serviced;
				break;
			case 7:
				$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',$type],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*')
				->get();
				foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $locked[(int)$month]+=$record->total;
				}
			    $series=$locked;
				break;
			
		}			
		
		
		return Response::json($series);
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

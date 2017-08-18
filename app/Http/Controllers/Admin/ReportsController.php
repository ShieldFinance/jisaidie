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
		$Loans = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->join('organization', 'organization.id', '=', 'customers.organization_id')
				->select('loans.*','customers.mobile_number')
				->get();
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
		
		
		$disbursed_total=$pending_total=$serviced_total=$declined_total=0;
		$disbursed_total_amount=$pending_total_amount=$serviced_total_amount=$declined_total_amount=$invoiced_total_amount=$serviced_revenue=0;
		$total_borrowers_array=$disbursed=array();
		$repeats=array();
		
		foreach($Loans as $record){
			$month_year=date("F-Y",strtotime($record->created_at));
			$disbursed[$month_year]=$serviced[$month_year]=$pending[$month_year]=$pending[$month_year]=$invoiced[$month_year]=$declined[$month_year]=0;
		}
		foreach($Loans as $record){
			
			
			$month = date("m",strtotime($record->created_at));
				
				$month_year=date("F-Y",strtotime($record->created_at));
				
				
				$total_borrowers_array[trim($record->mobile_number)]=1;
				switch($record->status){
				case 2:
					$pending_total_amount=$pending_total_amount+$record->amount_requested;
					$pending_total=$pending_total+1;
					$pending[$month_year]+=$record->amount_requested;
					break;
				case 3:
					$declined_total_amount=$declined_total_amount+$record->amount_requested;
					$declined_total=$declined_total+1;
					$declined[$month_year]+=$record->total;
					break;
				case 5:
					
					$disbursed_total_amount=$disbursed_total_amount+$record->amount_requested;
					$disbursed_total=$disbursed_total+1;
					
					$disbursed[$month_year]+=$record->amount_requested;
					break;
				case 6:
					
					$serviced_revenue=$serviced_revenue+(($record->amount_processed-$record->amount_requested)+($record->total-$record->amount_processed));
					$serviced_total_amount=$serviced_total_amount+$record->amount_requested;
					$serviced_total=$serviced_total+1;
					$serviced[$month_year]+=$record->total;
					break;
				default:
					break;
				}
			    if($record->invoiced){
					$invoiced_total_amount=$invoiced_total_amount+$record->total;
					$invoiced_total=$invoiced_total+1;
					$invoiced[$month_year]+=$record->total;
				}
			
			
			
		}
		
		$total_borrowers=count($total_borrowers_array);
			
		
		if($serviced_total>0)
		$rev_per_employee=number_format($serviced_revenue/$total_borrowers,2);
		else
		$rev_per_employee=0;
		
		if($serviced_total>0)
		$rev_per_advance=number_format($serviced_revenue/$serviced_total,2);
		else
		$rev_per_advance=0;
		
		if($serviced_total>0)
		$advance_per_employee=number_format((($serviced_total_amount)/$total_borrowers),2);
		else
		$advance_per_employee=0;
		
		if($serviced_total>0)
		$aver_advance_per_advance=number_format((($serviced_total_amount)/($serviced_total)),2);
		else
		$aver_advance_per_advance=0;
		
                
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
	   
	
        $results = DB::select( DB::raw("SELECT customers.id FROM customers
                            INNER JOIN loans ON (customers.id = loans.customer_id)
                            where loans.id in (select id from loans
                            group by id having count(*) > 1)") );
        
        //echo '<pre>';print_r($results);exit;
	 
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
											
											'rev_per_advance' => $rev_per_advance,
											'advance_per_employee' => $advance_per_employee,
											'aver_advance_per_advance' => $aver_advance_per_advance,
											
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
	
	
	public function loanDataAverages(Request $request){
		$type=$request->input('type');
		switch($type){
			case 'a':
				
				return $this->getAverageRevenues($request);
				break;
			
			case 'b':
				return $this->getAverageRevenueLoans($request);
				break;
		
			case '':
				return $this->getAverageLoans($request);
				
				break;
		}
			
	}
	
	public function getAverageRevenues(Request $request){
		    $series=array();
			$company=$request->input('organization')?$request->input('organization'):0;//company from which to retrieve data
			$type=$request->input('type');//the type of loan data in view
			$year=$request->input('year')?$request->input('year'):date("Y");//year for viewing data
			
			$start_date=$year."-01-01 00:00:00";
			$end_date=$year."-12-31 11:59:59";
			$average_revenue=array();
			for($k=1;$k<13;$k++){
				$average_revenue[$k]=$revenues[$k]=0;
			}
			
			$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',6],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',6],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*','customers.mobile_number')
				->get();
			
			$total_borrowers_array=array();
			
			foreach($records as $record){
				 
				 $month = date("m",strtotime($record->created_at));
				 $total_borrowers_array[(int)$month][trim($record->mobile_number)]=1;
				 $revenues[(int)$month]+=($record->amount_processed-$record->amount_requested)+($record->total-$record->amount_processed);
			}
			
			
			foreach($revenues as $key=>$revenue){
				if(isset($total_borrowers_array[$key])){
				
				$average_revenue[$key]=$revenue/count($total_borrowers_array[$key]);
				
				}
			}
			
			
			return Response::json($average_revenue);
				
	}
	public function getAverageLoans(Request $request){
		    $series=array();
			$company=$request->input('organization')?$request->input('organization'):0;//company from which to retrieve data
			$type=$request->input('type');//the type of loan data in view
			$year=$request->input('year')?$request->input('year'):date("Y");//year for viewing data
			
			$start_date=$year."-01-01 00:00:00";
			$end_date=$year."-12-31 11:59:59";
			
			for($k=1;$k<13;$k++){
				$average_advances[$k]=$advances[$k]=0;
			}
			
			$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',6],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',6],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date]
							   ];
			
				}
				
				
				$records = DB::table('loans')
				->join('customers', 'customers.id', '=', 'loans.customer_id')
				->leftJoin('organization', 'organization.id', '=', 'customers.organization_id')
				->where($wheres)
				->select('loans.*','customers.mobile_number')
				->get();
			
			$total_borrowers_array=array();
			foreach($records as $record){
				 
				 $month = date("m",strtotime($record->created_at));
				 $total_borrowers_array[(int)$month][trim($record->mobile_number)]=1;
				 $advances[(int)$month]+=$record->total;
			}
			
			foreach($advances as $key=>$advance){
				if(isset($total_borrowers_array[$key])){
				   $average_advances[$key]=$advance/count($total_borrowers_array[$key]);
			    }
			}
			//echo '<pre>';print_r($average_advances);exit;
			return Response::json($average_advances);
	}
	//dashboard methods
	public function getAverageRevenueLoans(Request $request){
		    $series=array();
			$company=$request->input('organization')?$request->input('organization'):0;//company from which to retrieve data
			$year=$request->input('year')?$request->input('year'):date("Y");//year for viewing data
			
			$start_date=$year."-01-01 00:00:00";
			$end_date=$year."-12-31 11:59:59";
			$average_serviced_revenue=$advances_count=array();
			for($k=1;$k<13;$k++){
				$average_serviced_revenue[$k]=$advances_count[$k]=0;
			}
			
			$where=array();
				if($company){
				     $wheres = [
								['loans.status'  ,'=',6],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',6],
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
			
			$serviced_revenue=$advances_count=array();
			foreach($records as $record){
				 $month = date("m",strtotime($record->created_at));
				 $serviced_revenue[(int)$month]=$advances_count[(int)$month]=0;
			}
			foreach($records as $record){
				 
				 $month = date("m",strtotime($record->created_at));
				 if(isset($serviced_revenue[(int)$month])){
				 $serviced_revenue[(int)$month]=$serviced_revenue[(int)$month]+(($record->amount_processed-$record->amount_requested)+($record->total-$record->amount_processed));
			     $advances_count[(int)$month]=$advances_count[(int)$month]+1;
				 }
			}
			
			foreach($serviced_revenue as $month=>$serviced){
				$average_serviced_revenue[$month]=$serviced_revenue[$month]/$advances_count[$month];
			}
		    
			return Response::json($average_serviced_revenue);
	}
	public function getRevenues(){
		
			 $series=array();
			$company=$request->input('organization')?$request->input('organization'):0;//company from which to retrieve data
			$type=$request->input('type');//the type of loan data in view
			$year=$request->input('year')?$request->input('year'):date("Y");//year for viewing data
			
			$start_date=$year."-01-01 00:00:00";
			$end_date=$year."-12-31 11:59:59";
			if($company){
				     $wheres = [
								['loans.status'  ,'=',6],
								['loans.deleted','=', 0],
								['loans.created_at' ,'<=', $end_date],
								['loans.created_at','>=',$start_date],
								['organization.id' ,'=',$company]
							   ];
							
		
				}else{
					 $wheres = [
								['loans.status'  ,'=',6],
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
			//echo '<pre>';print_r($wheres);exit;
			
			$revenues=$processing_fee=$interest=array();
			
			for($k=1;$k<13;$k++){
				$revenues[$k]=$processing_fee[$k]=$interest[$k]=0;
			}
			
			foreach($records as $record){
				 $month = date("m",strtotime($record->created_on));
				 $processing_fee[(int)$month]+=$record->amount_processed-$record->amount_requested;
				 $interest[(int)$month]+=$record->total-$record->amount_processed;
				 $revenues[(int)$month]+=($record->amount_processed-$record->amount_requested)+($record->total-$record->amount_processed);
			}
			
			$months = array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "Apr", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec");
            $html='<table class="table table-bordered table-striped">
				<thead class="thin-border-bottom">
				<tr><th class="hidden-480">
							<i class="ace-icon fa fa-caret-right blue"></i>
							Label
					       </th>';
			foreach ($months as $m) {
		                $html.=' <th class="hidden-480">
							<i class="ace-icon fa fa-caret-right blue"></i>
							'.$m.'
					       </th>';
			}
			$html.='</tr>
			</thead><tbody><tr></td>
				<td>
				<b class="blue">Revenues</b>
				</td>';
			
			foreach($revenues as $revenue){
				$html.='</td>
				<td>
				<b class="blue">KES '.number_format($revenue,2).'</b>
				</td>';
			}
			$html.='</tr><tr></td>
				<td>
				<b class="blue">Processing Fee</b>
				</td>';
			
			foreach($processing_fee as $processing){
				$html.='</td>
				<td>
				<b class="blue">KES '.number_format($processing,2).'</b>
				</td>';
			}
			$html.='</tr><tr></td>
				<td>
				<b class="blue">Interest</b>
				</td>';
			foreach($interest as $int){
				$html.='</td>
				<td>
				<b class="blue">KES '.number_format($int,2).'</b>
				</td>';
			}
			
			$html.='</tr></tbody>
				</table>';
			echo $html;exit;
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

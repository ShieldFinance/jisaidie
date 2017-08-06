@extends('layouts.backend')

@section('content')
    <link rel="stylesheet" href="{{ URL::asset('css/AdminLTE.min.css') }}" />
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')
            {!! Charts::assets() !!}
            
            
            <div class="col-md-9">
            
               
                <div class="col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">Active Vs Inactive customers</div>
                        <div class="panel-body">
                             
                             <div class="col-sm-6"> {!! $active_inactive->render() !!}</div>
                            
                          
                        </div>
                    </div>
                </div>
                    
                <div class="col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">Verified Vs Unverified customers</div>
                        <div class="panel-body">
                             
                             <div class="col-sm-6"> {!! $verified_unverified_nco->render() !!}</div>
                            
                          
                        </div>
                    </div>
                </div>
            
            </div>
            <div class="col-md-9">
               <div class="col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">Active Check off Vs None Check off customers </div>
                        <div class="panel-body">
                             
                            {!! $co_nco_active->render() !!}
                            
                          
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">Inactive Check off Vs None Check off customers</div>
                        <div class="panel-body">
                             
                             <div class="col-sm-6"> {!! $co_nco_inactive->render() !!}</div>
                            
                          
                        </div>
                    </div>
                </div>
                
            
            </div>
            <div class="col-md-9">
            
                <div class="col-md-6">
                
                    <div class="panel panel-default">
                        <div class="panel-heading">Repeat Vs One-time customers</div>
                        <div class="panel-body">
                             
                             <div class="col-sm-6"> {!! $repeatBorrowers->render() !!}</div>
                            
                          
                        </div>
                    </div>
                </div>
                
            
            </div>
            <div class="col-md-9 pull-right">
            
                 <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">User Registration</div>
                        <div class="panel-body">
                        <div class="col-md-129 pull-right">
                         {{ Form::select('years_id', $years) }}
                        </div>
                         <div id="user_reg_container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                        </div>
                    </div>
                 </div>
                
            
            </div>
                
            <div class="col-md-9 pull-right">
            
                 <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Loan statistics</div>
                        <div class="panel-body">
                        
                            
                         
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showPendingAdvances()">
                                     <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Pending Approval</span>
                                       <span class="info-box-number">KES <small>{!!$loans_analytics['pending']!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showDisbursedAdvances()">
                                     <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Total Disbursed</span>
                                       <span class="info-box-number">KES <small>{!!$loans_analytics['disbursed']!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showDeclinedAdvances()">
                                     <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Declined</span>
                                       <span class="info-box-number">KES <small>{!!$loans_analytics['rejected']!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                           <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showServicedAdvances()">
                                     <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Serviced</span>
                                       <span class="info-box-number">KES <small>{!!$loans_analytics['paid']!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showLockedAdvances()">
                                     <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Locked</span>
                                       <span class="info-box-number">KES <small>{!!$loans_analytics['locked']!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                            
                        </div>
                    </div>
                 </div>
                
            
            </div>
            <div class="col-md-9 pull-right">
            
                 <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Average Numbers</div>
                        <div class="panel-body">
                        
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showLoanBorrowers()">
                                     <span class="info-box-icon bg-purple"><i class="fa fa-percent"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Loan/Borrower</span>
                                       <span class="info-box-number">KES <small>{!!$advance_per_employee!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showRevenueBorrowers()">
                                     <span class="info-box-icon bg-purple"><i class="fa fa-percent"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Revenue/Borrower</span>
                                       <span class="info-box-number">KES <small>{!!$rev_per_advance!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                            <div class="col-md-4 col-sm-8 col-xs-12">
                                   <div class="info-box" onclick="showRevenueLoans()">
                                     <span class="info-box-icon bg-purple"><i class="fa fa-percent"></i></span>
                         
                                     <div class="info-box-content">
                                       <span class="info-box-text">Revenue/Loan</span>
                                       <span class="info-box-number">KES <small>{!!$rev_per_advance!!}</small></span>
                                     </div>
                                     <!-- /.info-box-content -->
                                   </div>
                                   <!-- /.info-box -->
                            </div>
                    
											
                        </div>
                    </div>
                 </div>
                
            
            </div>
                
                
                
                
                
            
        </div>
    </div>
<script type="text/javascript" src="{{ URL::asset('js/reports.js') }}"></script>
        

<!-- modal for pending advances-->
<div id="modal-advances-pending-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                            Pending Loans
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="pending-year" id="pending-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_pending',$organizations_array, null, array( 'id' => 'filter_company_pending'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_pending',$loan_modes, null, array( 'id' => 'filter_mode_pending'))!!}
                                           
                                        </div>
                                            
                                           
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_pending" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>  

<!-- modal for disbursed advances-->
<div id="modal-advances-disbursed-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                            Disbursed advances
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="disbursed-year" id="disbursed-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_disbursed',$organizations_array, null, array( 'id' => 'filter_company_disbursed'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_disbursed',$loan_modes, null, array( 'id' => 'filter_mode_disbursed'))!!}
                                           
                                        </div>
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_disbursed" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>         
<!-- modal for declined advances-->
<div id="modal-advances-declined-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                            Declined Loans
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="declined-year" id="declined-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_declined',$organizations_array, null, array( 'id' => 'filter_company_declined'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_declined',$loan_modes, null, array( 'id' => 'filter_mode_declined'))!!}
                                           
                                        </div>
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_declined" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>
<!-- modal for Serviced advances-->
<div id="modal-advances-serviced-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                            Serviced Loans
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="serviced-year" id="serviced-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_serviced',$organizations_array, null, array( 'id' => 'filter_company_serviced'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_serviced',$loan_modes, null, array( 'id' => 'filter_mode_serviced'))!!}
                                           
                                        </div>
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_serviced" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>
<!-- modal for locked advances-->
<div id="modal-advances-locked-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                            Locked Loans
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="locked-year" id="locked-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_locked',$organizations_array, null, array( 'id' => 'filter_company_locked'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_locked',$loan_modes, null, array( 'id' => 'filter_mode_locked'))!!}
                                           
                                        </div>
                                            
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_locked" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>
<!-- modal for loan-borrower advances-->
<div id="modal-advances-loan-borrower-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                           Average Loan per borrower
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="loan-borrower-year" id="loan-borrower-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_loan_borrower',$organizations_array, null, array( 'id' => 'filter_company_loan_borrower'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_loan_borrower',$loan_modes, null, array( 'id' => 'filter_mode_loan_borrower'))!!}
                                           
                                        </div>
                                            
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_loan_borrower" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>
<!-- modal for revenue-borrower advances-->
<div id="modal-advances-revenue-borrower-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                            Average Revenue per borrower
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="revenue-borrower-year" id="revenue-borrower-year"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_revenue_borrower',$organizations_array, null, array( 'id' => 'filter_company_locked'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_revenue_borrower',$loan_modes, null, array( 'id' => 'filter_mode_locked'))!!}
                                           
                                        </div>
                                            
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_revenue_borrower" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>
<!-- modal for revenue-loan advances-->
<div id="modal-advances-revenue-loan-month" class="modal" tabindex="-1">
            <div class="modal-dialog"  style="width:1200px !important; ">
               <div class="modal-content">
                 <div class="modal-header">
                     <button class="close" data-dismiss="modal" type="button">×</button>
                     <div class="row">
                        <div class="col-xs-2 col-sm-4">
                            <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-arrow-up orange"></i>
                           Average Revenue per loan
                            </h4>
                        </div>
                        <div class="col-xs-10 col-sm-8">
                          
                               
                                 <div class="row">
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                          Year:  <select name="revenue_loan" id="revenue_loan"></select>
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Organization:   {!!Form::select('filter_company_revenue_loan',$organizations_array, null, array( 'id' => 'filter_company_revenue_loan'))!!}
                                           
                                        </div>
                                        <div class="col-md-4 col-sm-8 col-xs-12">
                                          
                                         Type:   {!!Form::select('filter_mode_revenue_loan',$loan_modes, null, array( 'id' => 'filter_mode_revenue_loan'))!!}
                                           
                                        </div>
                                            
                                 </div>
                                
                        </div>
                    </div>
                 
                 
                 
                 
                 </div>
                 
                 <div class="modal-body">
                
                    <div id="container_revenue_loan" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
                 
                 </div>
                 <div class="modal-footer">
                
                 <button class="btn btn-sm pull-right" data-dismiss="modal">
                     <i class="ace-icon fa fa-times"></i>
                     Close
                 </button>	
                 </div>
               </div>
            </div>
</div>  
        
        
@endsection

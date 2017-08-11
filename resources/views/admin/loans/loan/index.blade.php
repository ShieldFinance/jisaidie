@extends('layouts.backend')

@section('content')
<SCRIPT language="javascript">
$(function(){

	// add multiple select / deselect functionality
	$("#selectall").click(function () {
            $('.loan_cbx').attr('checked', this.checked);
	});

	// if all checkbox are selected, check the selectall checkbox
	// and viceversa
	$(".loan_cbx").click(function(){

		if($(".loan_cbx").length == $(".loan_cbx:checked").length) {
			$("#selectall").attr("checked", "checked");
		} else {
			$("#selectall").removeAttr("checked");
		}

	});
        
         $(".process_loan").click(function(e){
            var selectedvalue = [];
            if ($(':checkbox:checked').length > 0) {
              $(':checkbox:checked').each(function (i) {
                  selectedvalue[i] = $(this).val();

              });
              $("#service").val($(this).data('service'));
              $(".selected_loans").val(selectedvalue);//this will pass as array and method will be POST
              var form = $(e.target).data('form');
              $('.'+form).submit();
             }else if($(this).data('service')=='ExportLoans'){
                  $("#service").val($(this).data('service'));
                 $('.loans_form').submit();
             }else if(typeof($(this).data('service'))!='undefined'){
                 alert("Please select at least one item from the list")
                 return false;
             }
             
             
        });
        
        $('.service_type').on('click',function(){
            var selected = $('input[name="service_type"]:checked').val();
            if(selected=='service_document'){
                $('.service_file').removeClass('hide')
            }else{
                if(!$('.service_file').hasClass('hide')){
                    $('.service_file').addClass('hide')
                }
            }
        })
       
       $('[rel="popover"]').popover({
        container: 'body',
        html: true,
        placement:'bottom',
        content: function () {
            var clone = $($(this).data('popover-content')).clone(true).removeClass('hide');
            return clone;
        }
    }).click(function(e) {
        e.preventDefault();
    });
       });
       
       $(document).ready(function () {
    $(".btn-select").each(function (e) {
        var value = $(this).find("ul li.selected").html();
        if (value != undefined) {
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value").html(value);
        }
    });
});

$(document).on('click', '.btn-select', function (e) {
    e.preventDefault();
    var ul = $(this).find("ul");
    if ($(this).hasClass("active")) {
        if (ul.find("li").is(e.target)) {
            var target = $(e.target);
            target.addClass("selected").siblings().removeClass("selected");
            var value = target.html();
            $(this).find(".btn-select-input").val(value);
            $(this).find(".btn-select-value").html(value);
        }
        ul.hide();
        $(this).removeClass("active");
    }
    else {
        $('.btn-select').not(this).each(function () {
            $(this).removeClass("active").find("ul").hide();
        });
        ul.slideDown(300);
        $(this).addClass("active");
    }
});

$(document).on('click', function (e) {
    var target = $(e.target).closest(".btn-select");
    if (!target.length) {
        $(".btn-select").removeClass("active").find("ul").hide();
    }
});

</SCRIPT>
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Loan</div>
                    <div class="panel-body">
                        {!! $action_buttons !!}

                        <br/>
                        <br/>
                          {!! Form::open(['method' => 'POST', 'url' => '/admin/loans/process_loan', 'class' => 'navbar-form navbar-right loans_form'])  !!}
                             <input type="hidden" name="loans" value="" class="selected_loans"/>
                             <input type="hidden" name="service" value="" id="service"/>
                           {!! Form::close() !!}
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox"  id="selectall" /></th>
                                        <th>#</th>
                                        <th>Account</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Amount Requested</th>
                                        <th>Amount Processed</th>
                                        <th>Total</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Date Disbursed</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($loans as $item)
                                    <tr>
                                        <td><input type="checkbox"  class="loan_cbx" value="{{$item->id}}"/></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <b>Phone:</b> {{ $item->mobile_number }}<br>
                                            <b>Email:</b> {{ $item->email}}<br>
                                            <b>Id No.</b> {{ $item->id_number}}
                                        </td>
                                        <td>{{$item->customer_name}}</td>
                                         <td>{{ $item->type }}</td>
                                        <td>{{ $item->amount_requested }}</td>
                                        <td>{{ $item->amount_processed }}</td>
                                        <td>{{ number_format($item->total,2,'.',',')}}</td>
                                        <td>{{ number_format($item->total-$item->paid,2,'.',',')}}</td>
                                        <td>{{ array_search ($item->status, config('app.loanStatus')) }}</td>
                                        <td>{{ date('d, M Y H:i:s',strtotime($item->date_disbursed)) }}</td>
                                        <td>
                                            <a href="{{ url('/admin/loan/' . $item->id) }}" title="View Loan"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $loans->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="myPopover" class="hide">
{!! Form::open(['method' => 'GET', 'url' => '/admin/loan', 'class' => 'search_form', 'role' => 'search'])  !!}
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Search...">
        
    </div>
 <div class="input-group">
      <label for="">Organization</label>
     <select class="form-control" name="search_organization">
         <option value=''>Select</option>
         <?php if(isset($organizations)){ ?>
          @foreach($organizations as $organization)
         <option value='{{$organization->id}}'>{{$organization->name}}</option>
         @endforeach
         <?php } ?>
     </select>
    </div>
<div class="input-group">
    <label for="">Loan type</label>
     <select class="form-control" name="search_type">
         <option value="">Select</option>
         <option value='co'>Checkoff</option>
         <option value='nco'>Non Checkoff</option>
     </select>
    </div>
<div class="input-group">
    <label for="">Loan Status</label>
     <select class="form-control" name="search_status">
         <option value="">Select</option>
         <option value="{{ config('app.loanStatus')['pending']}}">Pending</option>
         <option value='{{ config('app.loanStatus')['approved']}}'>Approved</option>
         <option value='{{ config('app.loanStatus')['disbursed']}}'>Disbursed</option>
         <option value='{{ config('app.loanStatus')['rejected']}}'>Rejected</option>
          <option value='{{ config('app.loanStatus')['locked']}}'>Locked</option>
          <option value='{{ config('app.loanStatus')['paid']}}'>Paid</option>
     </select>
    </div>
<div style="margin-top:5px; ">
    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
    <button type="submit" class="btn btn-default pull-right"><i class="fa fa-stop"></i> Clear</button>

</div>
{!! Form::close() !!}
</div> 

<div id="invoicePopover" class="hide">
{!! Form::open(['method' => 'GET', 'url' => '/admin/loan', 'class' => 'search_form', 'role' => 'search'])  !!}
   
 <div class="input-group">
      <label for="">Organization</label>
     <select class="form-control" name="invoice_organization">
         <option value=''>Select</option>
         <?php if(isset($organizations)){ ?>
          @foreach($organizations as $organization)
         <option value='{{$organization->id}}'>{{$organization->name}}</option>
         @endforeach
         <?php } ?>
     </select>
    </div>
<div style="margin-top:5px; ">
    <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Download invoice</button>
   

</div>
{!! Form::close() !!}
</div>

<div id="serviceLoan" class="hide">
{!! Form::open(['method' => 'POST', 'url' => '/admin/loan', 'class' => 'service_form', 'role' => 'search','files'=>'true'])  !!}
   
 <div class="input-group">
     <input type="radio" value="service_selected" class="service_type" name="service_type" >
      <label for="">Service selected loans</label>
      
    </div>
<div class="input-group">
     <input type="radio" value="service_document" class="service_type" name="service_type" >
      <label for="">Upload a document (excel)</label>
      
    </div>
<div class='hide service_file'>
<div class="input-group">
     <input  type="file" name="service_file" >
</div>

    <a href='/admin/loan/?download_sample=1'>Download sample</a>
</div>
<div style="margin-top:5px; ">
    <button data-form="service_form"  type="submit" class="btn btn-primary process_loan"><i class="fa fa-check"></i> Service Loans</button>
   

</div>
<input type="hidden" name="loans" value="" class="selected_loans"/>
{!! Form::close() !!}
</div>
@endsection

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
        
         $(".process_loan").click(function(){
            var selectedvalue = [];
            if ($(':checkbox:checked').length > 0) {
              $(':checkbox:checked').each(function (i) {
                  selectedvalue[i] = $(this).val();

              });
              $("#service").val($(this).data('service'));
              //$("#page").load("ajax_file.php?t_id="+selectedvalue);//this will pass as string and method will be GET
              //or
              $("#selected_loans").val(selectedvalue);//this will pass as array and method will be POST
              $('.loans_form').submit();
             }else if($(this).data('service')=='ExportLoans'){
                  $("#service").val($(this).data('service'));
                 $('.loans_form').submit();
             }else{
                 alert("Please select at least one item from the list")
             }
             
             
        });
       
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

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Loan</div>
                    <div class="panel-body">
                        {!! $action_buttons !!}

                        <br/>
                        <br/>
                          {!! Form::open(['method' => 'POST', 'url' => '/admin/loans/process_loan', 'class' => 'navbar-form navbar-right loans_form'])  !!}
                             <input type="hidden" name="loans" value="" id="selected_loans"/>
                             <input type="hidden" name="service" value="" id="service"/>
                           {!! Form::close() !!}
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox"  id="selectall" /></th><th>ID</th><th>Customer</th><th>Type</th><th>Amount Requested</th><th>Amount Processed</th><th>Status<th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($loan as $item)
                                    <tr>
                                        <td><input type="checkbox"  class="loan_cbx" value="{{$item->id}}"/></td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->customer->mobile_number }}</td>
                                         <td>{{ $item->type }}</td>
                                        <td>{{ $item->amount_requested }}</td>
                                        <td>{{ $item->amount_processed }}</td>
                                        <td>{{ array_search ($item->status, config('app.loanStatus')) }}</td>
                                        <td>
                                            <a href="{{ url('/admin/loan/' . $item->id) }}" title="View Loan"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $loan->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="myPopover" class="hide">
{!! Form::open(['method' => 'GET', 'url' => '/admin/loan', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
    <div class="input-group">
        <input type="text" class="form-control" name="search" placeholder="Search...">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit">
                <i class="fa fa-search"></i>
            </button>
        </span>
    </div>
 <div class="input-group">
      <div class="col-xs-6 col-sm-3">
            <a class="btn btn-info btn-select btn-select-light">
                <input type="hidden" class="btn-select-input" id="" name="" value="" />
                <span class="btn-select-value">Select an Item</span>
                <span class='btn-select-arrow glyphicon glyphicon-chevron-down'></span>
                <ul>
                    <li>Option 1</li>
                    <li>Option 2</li>
                    <li>Option 3</li>
                    <li>Option 4</li>
                </ul>
            </a>
        </div>
    </div>
{!! Form::close() !!}
</div
@endsection

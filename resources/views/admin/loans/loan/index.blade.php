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
             }
             
        });
       
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

                        {!! Form::open(['method' => 'GET', 'url' => '/admin/loan', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search...">
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                        </div>
                        {!! Form::close() !!}

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
                                            <a href="{{ url('/admin/loan/' . $item->id . '/edit') }}" title="Edit Loan"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/admin/loan', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete Loan',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
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
@endsection

@extends('layouts.backend')

@section('content')
<SCRIPT language="javascript">
$(function(){

	// add multiple select / deselect functionality
	$("#selectall").click(function () {
            $('.customer_cbx').attr('checked', this.checked);
	});

	// if all checkbox are selected, check the selectall checkbox
	// and viceversa
	$(".customer_cbx").click(function(){

		if($(".customer_cbx").length == $(".customer_cbx:checked").length) {
			$("#selectall").attr("checked", "checked");
		} else {
			$("#selectall").removeAttr("checked");
		}

	});
        
         $(".send_msg_btn").click(function(){
            var selectedvalue = [];
            if ($(':checkbox:checked').length > 0) {
              $(':checkbox:checked').each(function (i) {
                  selectedvalue[i] = $(this).val();

              });
              //$("#page").load("ajax_file.php?t_id="+selectedvalue);//this will pass as string and method will be GET
              //or
              $("#selected_customers").val(selectedvalue);//this will pass as array and method will be POST
              $('.customers_form').submit();
             }else{
                 alert('Please select one or more customers from the list')
             }
             
        });
        
        $('.export_customer').click(function(){
            $('.export_customers').submit();
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


</SCRIPT>

    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Customers</div>
                    <div class="panel-body">
                        <div class="col-sm-4">
    
</div>
                         {!! $action_buttons !!}
                        {!! Form::open(['method' => 'POST', 'url' => '/admin/customers/export', 'class' => 'navbar-form navbar-right export_customers'])  !!}
                        
                        {!! Form::close() !!}
                        <br/>
                        <div class="table-responsive">
                             {!! Form::open(['method' => 'POST', 'url' => '/admin/sendMessage', 'class' => 'navbar-form navbar-right customers_form'])  !!}
                             <input type="hidden" name="customers" value="" id="selected_customers"/>
                             {!! Form::close() !!}
                            <table class="table table-borderless">
                                <thead>
                                    <tr>

                                        <th><input type="checkbox"  id="selectall" /></th>
                                        <th>ID</th>
                                        <th>Mobile Number</th>
                                        <th>Account Number</th>
                                        <th>First Name</th>
                                        <th>Last name</th>
                                        <th>Other Name</th>
                                        <th>Organization</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>

                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $item)
                                    <tr>
                                        <td>
                                            <input type="checkbox"  class="customer_cbx" value="{{$item->mobile_number}}"/>
                                        </td>
                                         <td>{{ $loop->iteration }}</td>
                                         <td><strong>Mobile No.:</strong>{{ $item->mobile_number }}<br>
                                             <strong>Email:</strong>{{ $item->email}}
                                         </td>
                                            <td>{{ $item->id_number }}
                                             @if($item->id_verified==1)
                                         <i class="fa fa-check" aria-hidden="true"></i>
                                         @endif
                                         @if($item->id_verified==2 || $item->id_verified==3)
                                         <i class="fa fa-ban" aria-hidden="true"></i>
                                         @endif
                                         </td>


                                        <td>{{ $item->surname }}</td>
                                        <td>{{ $item->last_name }}</td>
                                        <td>{{ $item->other_name }}</td>
                                        <td>{{ $item->company_name }}</td>

                                        <td>{{ $item->status?"Active":"Inactive" }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a href="{{ url('/admin/customers/' . $item->id) }}" title="View Customer"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            @if(!Auth::user()->hasRole('Viewer'))
                                            <a href="{{ url('/admin/customers/' . $item->id . '/edit') }}" title="Edit Customer"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                           
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['/admin/customers', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn hide btn-danger btn-xs',
                                                        'title' => 'Delete Customer',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                                 {!! Form::close() !!}
                                                
                                              @if(!$item->status)    
                                                   {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['admin/customers/activate'],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                
                                                {!! Form::button('<i class="fa fa fa-check" aria-hidden="true"></i> Activate', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn  btn-success btn-xs',
                                                        'title' => 'Activate',
                                                        'onclick'=>'return confirm("Activate user account")'
                                                )) !!}
                                                <input name="_method" type="hidden" value="POST">
                                                <input type="hidden" name="customer_id" value="{{ $item->id }}">
                                            {!! Form::close() !!}
                                            
                                            @endif   
                                            @if($item->status)  
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['admin/customers/deactivate'],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                
                                                 {!! Form::button('<i class="fa fa-ban" aria-hidden="true"></i> Deactivate', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn  btn-danger btn-xs',
                                                        'title' => 'Deactivate',
                                                        'onclick'=>'return confirm("Deactivate")'
                                                )) !!}
                                                <input name="_method" type="hidden" value="POST">
                                                <input type="hidden" name="customer_id" value="{{ $item->id }}">
                                            {!! Form::close() !!}
                                             @endif
                                             
                                            @if($item->id_verified==0 || $item->id_verified==2 || $item->id_verified==3)  
                                            {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['admin/customers/verify'],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                
                                                 {!! Form::button('<i class="fa fa-check" aria-hidden="true"></i> Verify ID', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn  btn-warning btn-xs',
                                                        'title' => 'Verify ID',
                                                        'onclick'=>'return confirm("Verify ID?")'
                                                )) !!}
                                                <input name="_method" type="hidden" value="POST">
                                                <input type="hidden" name="customer_id" value="{{ $item->id }}">
                                            {!! Form::close() !!}
                                             @endif
                                            
                                            
                                            
                                                {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['admin/customers/reset_pin'],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                
                                                 {!! Form::button('<i class="fa fa-refresh" aria-hidden="true"></i> Reset Pin', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn  btn-danger btn-xs',
                                                        'title' => 'Reset Pin',
                                                        'onclick'=>'return confirm("Confirm reset pin?")'
                                                )) !!}
                                                <input name="_method" type="hidden" value="POST">
                                                <input type="hidden" name="customer_id" value="{{ $item->id }}">
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $customers->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


<div id="customerImport" class="hide">
{!! Form::open(['method' => 'POST', 'url' => '/admin/import_customers', 'class' => 'import_customers_form', 'role' => 'search','files'=>'true'])  !!}

<div class=''>
    <div class="input-group">

      <label for="">Organization</label>
      <select required="required" class="form-control" name="organization_id">
         <option value=''>Select</option>
         <?php if(isset($organizations)){ ?>
          @foreach($organizations as $organization)
         <option value='{{$organization->id}}'>{{$organization->name}}</option>
         @endforeach
         <?php } ?>
     </select>
    </div>
<div class="input-group">
     <label for="">Upload a document (excel)</label>
     <input  type="file" name="customers" >
</div>

    <a href='/admin/customers/?download_sample=1'>Download sample</a>
</div>
<div style="margin-top:5px; ">
    <button data-form="service_form"  type="submit" class="btn btn-primary process_loan"><i class="fa fa-check"></i> Import</button>
   

</div>
{!! Form::close() !!}
</div>
@endsection

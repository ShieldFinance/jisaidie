@extends('layouts.backend')

@section('content')

    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Payments</div>
                    <div class="panel-body">
                        {!! $action_buttons !!}


                        <br/>
                        <br/>
                        {!! Form::open(['method' => 'POST', 'url' => '/admin/payments/export', 'class' => 'navbar-form navbar-right payments_form'])  !!}
                             <input type="hidden" name="service" value="" id="service"/>
                           {!! Form::close() !!}
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Amount</th>
                                        <th>Currency</th>
                                        <th>Customer</th>
                                        <th>AT ref</th>
                                        <th>Provider ref</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($payments as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ number_format($item->amount,2,'.',',') }}</td>
                                        <td>{{ $item->currency }}</td>
                                        <td>
                                            <b>Name:</b>{{ $item->customer_name }}<br>
                                            <b>Phone:</b> {{ $item->mobile_number }}<br>
                                            <b>Email:</b> {{ $item->email}}<br>
                                            <b>Id No.</b> {{ $item->id_number}} 
                                        </td>
                                        <td>{{ $item->reference }}</td>
                                        <td>{{ $item->provider_reference }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->type }}</td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="{{ url('/admin/payments/' . $item->id) }}" title="View Payment"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                                            @can('can_assign_payment_to_loan')
                                             @if(!$item->customer_name)
                                                <a data-toggle="modal"  data-payment_id="{{ $item->id }}" data-target="#reconcileModal" class="btn btn-info btn-xs reconcile_btn">Assign to loan</a>
                                             @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $payments->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- line modal -->
<div class="modal fade" id="reconcileModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<h3 class="modal-title" id="lineModalLabel">Reconcile</h3>
		</div>
		<div class="modal-body">
			
            <!-- content goes here -->
            <form id="reconcile_form" method="POST" action='/reconcile_loan'>
              <div class="form-group">
                <label for="loan_id">Select Loan</label>
                <input type="text" class="form-control" id="loan_id_search" placeholder="Enter mobile number">
                <input type='text' id="payment_id" name="payment_id" value="">
                <input type='text' id="loan_id" name="loan_id" value="">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
              </div>
            </form>

		</div>
		<div class="modal-footer">
			<div class="btn-group btn-group-justified" role="group" aria-label="group button">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-default" data-dismiss="modal"  role="button">Close</button>
				</div>
				<div class="btn-group btn-delete hidden" role="group">
					<button type="button" id="cancel" class="btn btn-default btn-hover-red" data-dismiss="modal"  role="button">Delete</button>
				</div>
				<div class="btn-group" role="group">
					<button type="submit" id="submit_reconcile" class="btn btn-default btn-hover-green" data-action="save" role="button">Save</button>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
@endsection

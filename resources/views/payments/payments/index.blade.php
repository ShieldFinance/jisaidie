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
@endsection

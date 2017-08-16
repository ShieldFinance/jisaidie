@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Payments</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/payments/create') }}" class="btn btn-success btn-sm" title="Add New Payment">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        {!! Form::open(['method' => 'GET', 'url' => '/admin/payments', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
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
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a href="{{ url('/admin/payments/' . $item->id) }}" title="View Payment"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            
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

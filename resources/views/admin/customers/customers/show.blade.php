@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Customer {{ $customer->id }}</div>
                    <div class="panel-body">

                        <a href="{{ url('/admin/customers') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <a href="{{ url('/admin/customers/' . $customer->id . '/edit') }}" title="Edit Customer"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                       
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th>Phone Number</th><td>{{ $customer->mobile_number }}</td>
                                    </tr>
                                    <tr><th> First Name </th><td> {{ $customer->surname }} </td></tr>
                                    <tr><th> Last Name </th><td> {{ $customer->last_name }} </td></tr>
                                    <tr><th> Other Name </th><td> {{ $customer->other_name }} </td></tr>
                                    <tr><th> Witholding balance </th><td> {{ number_format($customer->withholding_balance,2,'.',',') }} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

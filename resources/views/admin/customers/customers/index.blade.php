@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Customers</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/customers/create') }}" class="btn btn-success btn-sm" title="Add New Customer">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>

                        {!! Form::open(['method' => 'GET', 'url' => '/admin/customers', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
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
                                        <th>ID</th><th>Mobile Number</th><th>Surname</th><th>Last name</th><th>Other Name</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($customers as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                         <td>{{ $item->mobile_number }}</td>
                                        <td>{{ $item->surname }}</td><td>{{ $item->last_name }}</td><td>{{ $item->other_name }}</td>
                                        <td>
                                            <a href="{{ url('/admin/customers/' . $item->id) }}" title="View Customer"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
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
                                                   {!! Form::open([
                                                'method'=>'POST',
                                                'url' => ['admin/customers/reset_pin'],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                 {!! Form::button('<i class="fa fa-refresh-o" aria-hidden="true"></i> Reset Pin', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn  btn-primary btn-xs',
                                                        'title' => 'Reset Pin',
                                                        'onclick'=>'return confirm("Confirm reset pin?")'
                                                )) !!}
                                                <input name="_method" type="hidden" value="POST">
                                                <input type="hidden" name="customer_id" value="{{ $item->id }}">
                                            {!! Form::close() !!}
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
@endsection

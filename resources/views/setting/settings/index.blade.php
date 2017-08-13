@extends('layouts.backend')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">Settings</div>
                    <div class="panel-body">
                        @can('can_add_settings')
                        <a href="{{ url('/admin/settings/create') }}" class="btn btn-success btn-sm hide" title="Add New Setting">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                        @endcan

                        {!! Form::open(['method' => 'GET', 'url' => '/admin/settings', 'class' => 'navbar-form navbar-right', 'role' => 'search'])  !!}
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
                                        <th>ID</th><th>Setting Name</th><th>Setting Value</th><th>Setting Description</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($settings as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->setting_name }}</td><td>{{ $item->setting_value }}</td><td>{{ $item->setting_description }}</td>
                                        <td>
                                           <!-- <a href="{{ url('/admin/settings/' . $item->id) }}" title="View Setting"><button class="btn btn-info btn-xs"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>-->
                                            @if(!Auth::user()->hasRole('Viewer'))
                                            <a href="{{ url('/admin/settings/' . $item->id . '/edit') }}" title="Edit Setting"><button class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            {!! Form::open([
                                                'method'=>'DELETE',
                                                'url' => ['/admin/settings', $item->id],
                                                'style' => 'display:inline'
                                            ]) !!}
                                                {!! Form::button('<i class="fa fa-trash-o" aria-hidden="true"></i> Delete', array(
                                                        'type' => 'submit',
                                                        'class' => 'btn btn-danger btn-xs',
                                                        'title' => 'Delete Setting',
                                                        'onclick'=>'return confirm("Confirm delete?")'
                                                )) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $settings->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

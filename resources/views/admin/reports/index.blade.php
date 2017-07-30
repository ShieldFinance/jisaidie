@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row">
            @include('admin.sidebar')
            {!! Charts::assets() !!}
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Users</div>
                    <div class="panel-body">
                         <div class="col-sm-6"> {!! $activeUsers->render() !!}</div>
                         <div class="col-sm-6"> {!! $verifiedUsers->render() !!}</div>
                        

                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">Check off Loans</div>
                    <div class="panel-body">
                         <div class="col-sm-6"> {!! $check_off_summary->render() !!}</div>
                         
                    </div>
                </div>
                    
                    
            </div>
        </div>
    </div>
@endsection

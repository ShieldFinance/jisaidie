<div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
    {!! Form::label('subject', 'Subject', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('subject', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    {!! Form::label('message', 'Message', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::textarea('message', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
    {!! Form::label('type', 'Type', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::select('type', array('sms' => 'SMS', 'email' => 'Email','inapp' => 'In App'), ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div>
    <div class="row">
        <div class="col-xs-5">
            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                <?php if(!empty($customers)){?>
                <?php foreach($customers as $customer){?>
                <option value="{{$customer->mobile_number}}">{{$customer->surname.' '.$customer->last_name.' ('.$customer->mobile_number.' , '.$customer->email.')'}}</option>
                <?php } ?>
                <?php } ?>
            </select>
        </div>
        
        <div class="col-xs-2">
            <button type="button" id="undo_redo_undo" class="btn btn-primary btn-block">undo</button>
            <button type="button" id="search_rightAll" class="btn btn-block"><i class="fa fa-forward"></i></button>
            <button type="button" id="search_rightSelected" class="btn btn-block"><i class="fa fa-chevron-right"></i></button>
            <button type="button" id="search_leftSelected" class="btn btn-block"><i class="fa fa-chevron-left"></i></button>
            <button type="button" id="search_leftAll" class="btn btn-block"><i class="fa fa-backward"></i></button>
        <button type="button" id="undo_redo_redo" class="btn btn-warning btn-block">redo</button>
            </div>
        
        <div class="col-xs-5">
            <select name="recipient[]" id="search_to" class="form-control" size="8" multiple="multiple">
            <?php if(!empty($selectedCustomers)){?>
                <?php foreach($selectedCustomers as $customer){?>
                <option value="{{$customer}}">{{$customer}}</option>
                <?php } ?>
                <?php } ?>
                </select>
        </div>
    </div>
<input type="hidden" name="status" value="pending">
<input type="hidden" name="attempts" value="0">
<input type="hidden" name="service_id" value="0">
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>

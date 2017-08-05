<div class="form-group{{ $errors->has('name') ? ' has-error' : ''}}">
    {!! Form::label('name', 'Name: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group{{ $errors->has('label') ? ' has-error' : ''}}">
    {!! Form::label('label', 'Label: ', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('label', null, ['class' => 'form-control']) !!}
        {!! $errors->first('label', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="row">
    <hr><h4>Permissions</h4>
        <div class="col-xs-5">
            <select name="from[]" id="search" class="form-control" size="8" multiple="multiple">
                <?php if(!empty($permissions)){?>
                <?php foreach($permissions as $perm){?>
                <option value="{{$perm->id}}">{{$perm->label}}</option>
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
            <select name="selected_permissions[]" id="search_to" class="form-control" size="8" multiple="multiple">
            <?php if(!empty($selectedPermissions)){?>
                <?php foreach($selectedPermissions as $selectedPerm){?>
                <option value="{{$selectedPerm->permission_id}}">{{$selectedPerm->permission_label}}</option>
                <?php } ?>
                <?php } ?>
                </select>
        </div>
    </div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Create', ['class' => 'btn btn-primary']) !!}
    </div>
</div>
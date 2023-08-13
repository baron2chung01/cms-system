<!-- Assets Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('assets_uuid', 'Assets Uuid:') !!}
    {!! Form::text('assets_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Module Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('module_uuid', 'Module Uuid:') !!}
    {!! Form::text('module_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Resource Path Field -->
<div class="form-group col-sm-6">
    {!! Form::label('resource_path', 'Resource Path:') !!}
    {!! Form::text('resource_path', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- File Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_name', 'File Name:') !!}
    {!! Form::text('file_name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('type', 'Type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- File Size Field -->
<div class="form-group col-sm-6">
    {!! Form::label('file_size', 'File Size:') !!}
    {!! Form::number('file_size', null, ['class' => 'form-control']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::number('status', null, ['class' => 'form-control']) !!}
</div>

<!-- Created By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('created_by', 'Created By:') !!}
    {!! Form::text('created_by', null, ['class' => 'form-control']) !!}
</div>

<!-- Updated By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('updated_by', 'Updated By:') !!}
    {!! Form::text('updated_by', null, ['class' => 'form-control']) !!}
</div>

<!-- Deleted By Field -->
<div class="form-group col-sm-6">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    {!! Form::text('deleted_by', null, ['class' => 'form-control']) !!}
</div>
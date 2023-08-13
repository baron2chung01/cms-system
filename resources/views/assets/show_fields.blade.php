<!-- Assets Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('assets_uuid', 'Assets Uuid:') !!}
    <p>{{ $assets->assets_uuid }}</p>
</div>

<!-- Module Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('module_uuid', 'Module Uuid:') !!}
    <p>{{ $assets->module_uuid }}</p>
</div>

<!-- Resource Path Field -->
<div class="col-sm-12">
    {!! Form::label('resource_path', 'Resource Path:') !!}
    <p>{{ $assets->resource_path }}</p>
</div>

<!-- File Name Field -->
<div class="col-sm-12">
    {!! Form::label('file_name', 'File Name:') !!}
    <p>{{ $assets->file_name }}</p>
</div>

<!-- Type Field -->
<div class="col-sm-12">
    {!! Form::label('type', 'Type:') !!}
    <p>{{ $assets->type }}</p>
</div>

<!-- File Size Field -->
<div class="col-sm-12">
    {!! Form::label('file_size', 'File Size:') !!}
    <p>{{ $assets->file_size }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $assets->status }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{{ $assets->created_by }}</p>
</div>

<!-- Updated By Field -->
<div class="col-sm-12">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{{ $assets->updated_by }}</p>
</div>

<!-- Deleted By Field -->
<div class="col-sm-12">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{{ $assets->deleted_by }}</p>
</div>


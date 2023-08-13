<!-- Materials Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('materials_uuid', 'Materials Uuid:') !!}
    {!! Form::text('materials_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Materials Categories Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('materials_categories_uuid', 'Materials Categories Uuid:') !!}
    {!! Form::text('materials_categories_uuid', null, ['class' => 'form-control']) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Code Field -->
<div class="form-group col-sm-6">
    {!! Form::label('code', 'Code:') !!}
    {!! Form::text('code', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('price', 'Price:') !!}
    {!! Form::number('price', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Group Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('group_price', 'Group Price:') !!}
    {!! Form::number('group_price', null, ['class' => 'form-control']) !!}
</div>

<!-- Group Qty Field -->
<div class="form-group col-sm-6">
    {!! Form::label('group_qty', 'Group Qty:') !!}
    {!! Form::number('group_qty', null, ['class' => 'form-control']) !!}
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
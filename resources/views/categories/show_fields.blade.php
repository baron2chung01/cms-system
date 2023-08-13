<!-- Categories Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('categories_uuid', 'Categories Uuid:') !!}
    <p>{{ $category->categories_uuid }}</p>
</div>

<!-- Parents Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('parents_uuid', 'Parents Uuid:') !!}
    <p>{{ $category->parents_uuid }}</p>
</div>

<!-- Code Field -->
<div class="col-sm-12">
    {!! Form::label('code', 'Code:') !!}
    <p>{{ $category->code }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $category->name }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $category->status }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{{ $category->created_by }}</p>
</div>

<!-- Updated By Field -->
<div class="col-sm-12">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{{ $category->updated_by }}</p>
</div>

<!-- Deleted By Field -->
<div class="col-sm-12">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{{ $category->deleted_by }}</p>
</div>


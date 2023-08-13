<!-- Blog Categories Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('blog_categories_uuid', 'Blog Categories Uuid:') !!}
    <p>{{ $blogCategory->blog_categories_uuid }}</p>
</div>

<!-- Parents Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('parents_uuid', 'Parents Uuid:') !!}
    <p>{{ $blogCategory->parents_uuid }}</p>
</div>

<!-- Code Field -->
<div class="col-sm-12">
    {!! Form::label('code', 'Code:') !!}
    <p>{{ $blogCategory->code }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $blogCategory->name }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $blogCategory->status }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{{ $blogCategory->created_by }}</p>
</div>

<!-- Updated By Field -->
<div class="col-sm-12">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{{ $blogCategory->updated_by }}</p>
</div>

<!-- Deleted By Field -->
<div class="col-sm-12">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{{ $blogCategory->deleted_by }}</p>
</div>


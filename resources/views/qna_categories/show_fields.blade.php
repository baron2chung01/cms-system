<!-- Categories Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('categories_uuid', 'Categories Uuid:') !!}
    <p>{{ $qnaCategory->categories_uuid }}</p>
</div>

<!-- Parents Uuid Field -->
<div class="col-sm-12">
    {!! Form::label('parents_uuid', 'Parents Uuid:') !!}
    <p>{{ $qnaCategory->parents_uuid }}</p>
</div>

<!-- Code Field -->
<div class="col-sm-12">
    {!! Form::label('code', 'Code:') !!}
    <p>{{ $qnaCategory->code }}</p>
</div>

<!-- Name Field -->
<div class="col-sm-12">
    {!! Form::label('name', 'Name:') !!}
    <p>{{ $qnaCategory->name }}</p>
</div>

<!-- Status Field -->
<div class="col-sm-12">
    {!! Form::label('status', 'Status:') !!}
    <p>{{ $qnaCategory->status }}</p>
</div>

<!-- Created By Field -->
<div class="col-sm-12">
    {!! Form::label('created_by', 'Created By:') !!}
    <p>{{ $qnaCategory->created_by }}</p>
</div>

<!-- Updated By Field -->
<div class="col-sm-12">
    {!! Form::label('updated_by', 'Updated By:') !!}
    <p>{{ $qnaCategory->updated_by }}</p>
</div>

<!-- Deleted By Field -->
<div class="col-sm-12">
    {!! Form::label('deleted_by', 'Deleted By:') !!}
    <p>{{ $qnaCategory->deleted_by }}</p>
</div>


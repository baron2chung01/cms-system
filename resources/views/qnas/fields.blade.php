<!-- Qna Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qna_uuid', 'Qna Uuid:') !!}
    {!! Form::text('qna_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Qna Categories Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('qna_categories_uuid', 'Qna Categories Uuid:') !!}
    {!! Form::text('qna_categories_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Title Field -->
<div class="form-group col-sm-6">
    {!! Form::label('title', 'Title:') !!}
    {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Date Field -->
<div class="form-group col-sm-6">
    {!! Form::label('date', 'Date:') !!}
    {!! Form::text('date', null, ['class' => 'form-control','id'=>'date']) !!}
</div>

@push('page_scripts')
    <script type="text/javascript">
        $('#date').datepicker()
    </script>
@endpush

<!-- Short Desc Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('short_desc', 'Short Desc:') !!}
    {!! Form::textarea('short_desc', null, ['class' => 'form-control']) !!}
</div>

<!-- Description Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('description', 'Description:') !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

<!-- Top Blog Field -->
<div class="form-group col-sm-6">
    <div class="form-check">
        {!! Form::hidden('top_blog', 0, ['class' => 'form-check-input']) !!}
        {!! Form::checkbox('top_blog', '1', null, ['class' => 'form-check-input']) !!}
        {!! Form::label('top_blog', 'Top Blog', ['class' => 'form-check-label']) !!}
    </div>
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
<!-- Shops Reviews Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shops_reviews_uuid', 'Shops Reviews Uuid:') !!}
    {!! Form::text('shops_reviews_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Shops Uuid Field -->
<div class="form-group col-sm-6">
    {!! Form::label('shops_uuid', 'Shops Uuid:') !!}
    {!! Form::text('shops_uuid', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Comment Field -->
<div class="form-group col-sm-12 col-lg-12">
    {!! Form::label('comment', 'Comment:') !!}
    {!! Form::textarea('comment', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Rating Field -->
<div class="form-group col-sm-6">
    {!! Form::label('rating', 'Rating:') !!}
    {!! Form::number('rating', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Product Desc Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_desc', 'Product Desc:') !!}
    {!! Form::number('product_desc', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Services Quality Field -->
<div class="form-group col-sm-6">
    {!! Form::label('services_quality', 'Services Quality:') !!}
    {!! Form::number('services_quality', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Product Categories Field -->
<div class="form-group col-sm-6">
    {!! Form::label('product_categories', 'Product Categories:') !!}
    {!! Form::number('product_categories', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Logistic Services Field -->
<div class="form-group col-sm-6">
    {!! Form::label('logistic_services', 'Logistic Services:') !!}
    {!! Form::number('logistic_services', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Geographical Location Field -->
<div class="form-group col-sm-6">
    {!! Form::label('geographical_location', 'Geographical Location:') !!}
    {!! Form::number('geographical_location', null, ['class' => 'form-control', 'required']) !!}
</div>

<!-- Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', 'Status:') !!}
    {!! Form::number('status', null, ['class' => 'form-control', 'required']) !!}
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopsReview extends Model
{
    public $table = 'shops_reviews';

    public $fillable = [
        'shops_reviews_uuid',
        'shops_uuid',
        'comment',
        'rating',
        'product_desc',
        'services_quality',
        'product_categories',
        'logistic_services',
        'geographical_location',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'shops_reviews_uuid' => 'string',
        'shops_uuid' => 'string',
        'comment' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'shops_reviews_uuid' => 'required|string',
        'shops_uuid' => 'required|string',
        'comment' => 'required|string',
        'rating' => 'required',
        'product_desc' => 'required',
        'services_quality' => 'required',
        'product_categories' => 'required',
        'logistic_services' => 'required',
        'geographical_location' => 'required',
        'status' => 'required',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

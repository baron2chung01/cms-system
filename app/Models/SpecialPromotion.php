<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialPromotion extends Model
{
    public $table = 'special_promotions';

    public $fillable = [
        'special_promotion_uuid',
        'code',
        'name',
        'short_description',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'special_promotion_uuid' => 'string',
        'code' => 'string',
        'name' => 'string',
        'short_description' => 'string',
        'description' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'special_promotion_uuid' => 'required|string',
        'code' => 'required|string',
        'name' => 'required|string',
        'short_description' => 'required|string',
        'description' => 'required|string',
        'status' => 'nullable',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

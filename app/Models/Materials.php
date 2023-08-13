<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materials extends Model
{
    public $table = 'materials';

    public $fillable = [
        'materials_uuid',
        'materials_categories_uuid',
        'name',
        'code',
        'price',
        'group_price',
        'group_qty',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'materials_uuid' => 'string',
        'materials_categories_uuid' => 'string',
        'name' => 'string',
        'code' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'materials_uuid' => 'required|string',
        'materials_categories_uuid' => 'nullable|string',
        'name' => 'required|string',
        'code' => 'required|string',
        'price' => 'required',
        'group_price' => 'nullable',
        'group_qty' => 'nullable',
        'status' => 'nullable',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

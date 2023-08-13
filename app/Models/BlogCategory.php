<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    public $table = 'blog_categories';

    public $fillable = [
        'blog_categories_uuid',
        'parents_uuid',
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'blog_categories_uuid' => 'string',
        'parents_uuid' => 'string',
        'code' => 'string',
        'name' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'blog_categories_uuid' => 'required|string',
        'parents_uuid' => 'nullable|string',
        'code' => 'required|string',
        'name' => 'required|string',
        'status' => 'nullable',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

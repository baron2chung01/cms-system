<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public $table = 'blogs';

    public $fillable = [
        'blog_uuid',
        'blog_categories_uuid',
        'title',
        'code',
        'date',
        'short_desc',
        'description',
        'top_blog',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'blog_uuid' => 'string',
        'blog_categories_uuid' => 'string',
        'title' => 'string',
        'code' => 'string',
        'date' => 'date',
        'short_desc' => 'string',
        'description' => 'string',
        'top_blog' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'blog_uuid' => 'required|string',
        'blog_categories_uuid' => 'required|string',
        'title' => 'required|string',
        'code' => 'required|string',
        'date' => 'required',
        'short_desc' => 'nullable|string',
        'description' => 'nullable|string',
        'top_blog' => 'required|boolean',
        'status' => 'nullable',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

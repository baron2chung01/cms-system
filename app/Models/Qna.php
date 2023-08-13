<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qna extends Model
{
    public $table = 'qna';

    public $fillable = [
        'qna_uuid',
        'qna_categories_uuid',
        'title',
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
        'qna_uuid' => 'string',
        'qna_categories_uuid' => 'string',
        'title' => 'string',
        'date' => 'date',
        'short_desc' => 'string',
        'description' => 'string',
        'top_blog' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'qna_uuid' => 'required|string',
        'qna_categories_uuid' => 'required|string',
        'title' => 'required|string',
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

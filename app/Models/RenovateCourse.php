<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RenovateCourse extends Model
{
    public $table = 'renovate_courses';

    public $fillable = [
        'renovate_courses_uuid',
        'title',
        'name',
        'code',
        'short_desc',
        'description',
        'location',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'renovate_courses_uuid' => 'string',
        'title' => 'string',
        'name' => 'string',
        'code' => 'string',
        'short_desc' => 'string',
        'description' => 'string',
        'location' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'renovate_courses_uuid' => 'required|string',
        'title' => 'required|string',
        'name' => 'required|string',
        'code' => 'required|string',
        'short_desc' => 'required|string',
        'description' => 'required|string',
        'location' => 'required|string',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

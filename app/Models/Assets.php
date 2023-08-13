<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    public $table = 'assets';

    public $fillable = [
        'assets_uuid',
        'module_uuid',
        'resource_path',
        'file_name',
        'type',
        'file_size',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'assets_uuid' => 'string',
        'module_uuid' => 'string',
        'resource_path' => 'string',
        'file_name' => 'string',
        'type' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'assets_uuid' => 'required|string',
        'module_uuid' => 'required|string',
        'resource_path' => 'required|string',
        'file_name' => 'required|string',
        'type' => 'required|string',
        'file_size' => 'nullable',
        'status' => 'nullable',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

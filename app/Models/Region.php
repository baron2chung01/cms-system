<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public $table = 'regions';

    public $fillable = [
        'regions_uuid',
        'name',
        'code',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'regions_uuid' => 'string',
        'name' => 'string',
        'code' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'regions_uuid' => 'required|string',
        'name' => 'required|string',
        'code' => 'required|string',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

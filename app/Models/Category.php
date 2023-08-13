<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use SoftDeletes;
    use HasFactory;

    const ACTIVE   = 0;
    const INACTIVE = 1;
    const DRAFT    = 2;
    const DELETE   = 3;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
        self::DRAFT    => 'Draft',
        self::DELETE   => 'Delete',
    ];

    public $table = 'categories';

    public $fillable = [
        'categories_uuid',
        'parents_uuid',
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'categories_uuid' => 'string',
        'parents_uuid' => 'string',
        'code' => 'string',
        'name' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'categories_uuid' => 'required|string',
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

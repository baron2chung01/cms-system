<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assets extends Model
{
    use SoftDeletes;
    use HasFactory;

    const ACTIVE   = 0;
    const APPROVED = 1; // used for receipt
    const REJECTED = 2; // used for receipt
    const DELETE   = 3;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::APPROVED => 'Approved',
        self::REJECTED => 'Rejected',
        self::DELETE   => 'Delete',
    ];

    public $table = 'assets';

    public $fillable = [
        'assets_uuid',
        'module_uuid',
        'second_module_uuid',
        'module_type',
        'resource_path',
        'file_name',
        'type',
        'file_size',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'assets_uuid'        => 'string',
        'module_uuid'        => 'string',
        'second_module_uuid' => 'string',
        'module_type'        => 'integer',
        'resource_path'      => 'string',
        'file_name'          => 'string',
        'type'               => 'string',
        'created_by'         => 'string',
        'updated_by'         => 'string',
        'deleted_by'         => 'string',
    ];

    public static $rules = [
        'assets_uuid'        => 'required|string',
        'module_uuid'        => 'required|string',
        'second_module_uuid' => 'nullable|string',
        'module_type'        => 'nullable',
        'resource_path'      => 'required|string',
        'file_name'          => 'required|string',
        'type'               => 'required|string',
        'file_size'          => 'nullable',
        'status'             => 'nullable',
        'created_by'         => 'nullable|string',
        'updated_by'         => 'nullable|string',
        'deleted_by'         => 'nullable|string',
        'created_at'         => 'nullable',
        'updated_at'         => 'nullable',
        'deleted_at'         => 'nullable',
    ];

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }
}

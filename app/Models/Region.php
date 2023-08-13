<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'regions';

    public $fillable = [
        'regions_uuid',
        'name',
        'code',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'regions_uuid' => 'string',
        'name'         => 'string',
        'code'         => 'string',
        'created_by'   => 'string',
        'updated_by'   => 'string',
        'deleted_by'   => 'string',
    ];

    public static $rules = [
        'regions_uuid' => 'required|string',
        'name'         => 'required|string',
        'code'         => 'required|string',
        'created_by'   => 'nullable|string',
        'updated_by'   => 'nullable|string',
        'deleted_by'   => 'nullable|string',
        'created_at'   => 'nullable',
        'updated_at'   => 'nullable',
        'deleted_at'   => 'nullable',
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class, 'regions_uuid', 'regions_uuid');
    }
}
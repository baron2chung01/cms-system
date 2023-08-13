<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RenovateCases extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'renovate_cases';

    public $fillable = [
        'renovate_cases_uuid',
        'title',
        'name',
        'short_desc',
        'description',
        'location',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'renovate_cases_uuid' => 'string',
        'title'               => 'string',
        'name'                => 'string',
        'short_desc'          => 'string',
        'description'         => 'string',
        'location'            => 'string',
        'created_by'          => 'string',
        'updated_by'          => 'string',
        'deleted_by'          => 'string',
    ];

    public static $rules = [
        'renovate_cases_uuid' => 'required|string',
        'title'               => 'required|string',
        'name'                => 'required|string',
        'short_desc'          => 'required|string',
        'description'         => 'required|string',
        'location'            => 'required|string',
        'created_by'          => 'nullable|string',
        'updated_by'          => 'nullable|string',
        'deleted_by'          => 'nullable|string',
        'created_at'          => 'nullable',
        'updated_at'          => 'nullable',
        'deleted_at'          => 'nullable',
    ];

}
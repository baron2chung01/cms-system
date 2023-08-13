<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $table = 'articles';

    public $fillable = [
        'articles_uuid',
        'code',
        'name',
        'title',
        'desc',
        'short_desc',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'articles_uuid' => 'string',
        'code'          => 'string',
        'name'          => 'string',
        'title'         => 'string',
        'desc'          => 'string',
        'short_desc'    => 'string',
        'created_by'    => 'string',
        'updated_by'    => 'string',
        'deleted_by'    => 'string',
    ];

    public static $rules = [
        'articles_uuid' => 'required|string',
        'code'          => 'required|string',
        'name'          => 'required|string',
        'title'         => 'nullable|string',
        'desc'          => 'nullable|string',
        'short_desc'    => 'nullable|string',
        'created_by'    => 'nullable|string',
        'updated_by'    => 'nullable|string',
        'deleted_by'    => 'nullable|string',
        'created_at'    => 'nullable',
        'updated_at'    => 'nullable',
        'deleted_at'    => 'nullable',
    ];

}
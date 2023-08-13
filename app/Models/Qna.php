<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Qna extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public $table = 'qna';

    public $fillable = [
        'qna_uuid',
        'qna_categories_uuid',
        'title',
        'date',
        'short_desc',
        'description',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'qna_uuid'            => 'string',
        'qna_categories_uuid' => 'string',
        'title'               => 'string',
        'date'                => 'date',
        'short_desc'          => 'string',
        'description'         => 'string',
        'created_by'          => 'string',
        'updated_by'          => 'string',
        'deleted_by'          => 'string',
    ];

    public static $rules = [
        'qna_uuid'            => 'required|string',
        'qna_categories_uuid' => 'required|string',
        'title'               => 'required|string',
        'date'                => 'required',
        'short_desc'          => 'nullable|string',
        'description'         => 'nullable|string',
        'status'              => 'nullable',
        'created_by'          => 'nullable|string',
        'updated_by'          => 'nullable|string',
        'deleted_by'          => 'nullable|string',
        'created_at'          => 'nullable',
        'updated_at'          => 'nullable',
        'deleted_at'          => 'nullable',
    ];

    public function category()
    {
        return $this->belongsTo(QnaCategory::class, 'qna_categories_uuid', 'categories_uuid');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

}
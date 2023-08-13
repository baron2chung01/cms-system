<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;
    use HasFactory;

    const ACTIVE = 0;
    const INACTIVE = 1;
    const DRAFT = 2;
    const DELETE = 3;

    const STATUS = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
        self::DRAFT => 'Draft',
        self::DELETE => 'Delete',
    ];

    public $table = 'blogs';

    public $fillable = [
        'blog_uuid',
        'blog_categories_uuid',
        'title',
        'code',
        'date',
        'short_desc',
        'description',
        'top_blog',
        'status',
        'views',
        'url',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'blog_uuid' => 'string',
        'blog_categories_uuid' => 'string',
        'title' => 'string',
        'code' => 'string',
        'date' => 'string',
        'short_desc' => 'string',
        'description' => 'string',
        'top_blog' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string',
    ];

    public static $rules = [
        'blog_uuid' => 'required|string',
        'blog_categories_uuid' => 'required|string',
        'title' => 'required|string',
        'code' => 'required|string',
        'date' => 'required',
        'short_desc' => 'nullable|string',
        'description' => 'nullable|string',
        'top_blog' => 'required|boolean',
        'status' => 'nullable',
        'views' => 'required|integer|between:0,2147483647',
        'url' => 'nullable|string',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_categories_uuid', 'blog_categories_uuid');
    }

    public function assets()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'blog_uuid');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

}

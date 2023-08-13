<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RenovateCourseCategory extends Model
{
    use SoftDeletes, HasFactory;

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

    public $table = 'renovate_course_categories';

    public $fillable = [
        'course_categories_uuid',
        'parents_uuid',
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
        'deleted_at'
    ];

    protected $casts = [
        'code' => 'string',
        'name' => 'string',
        'status' => 'integer'
    ];

    public static $rules = [
        'course_categories_uuid' => 'required|string',
        'parents_uuid' => 'nullable|string',
        'code' => 'required|string',
        'name' => 'required|string|max:255',
        'status' => 'required|integer',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'deleted_at' => 'nullable|string'
    ];

    public function courses()
    {
        return $this->hasMany(RenovateCourse::class, 'course_categories_uuid', 'course_categories_uuid');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

    public function displayParents(): Attribute
    {
        $nameCollection = self::where('course_categories_uuid', $this->parents_uuid)->pluck('name');
        if (!$nameCollection->isEmpty()) {
            return Attribute::make(
                get:fn() => $nameCollection[0],
            );
        } else {
            return Attribute::make(
                get:fn() => null,
            );

        }

    }
}

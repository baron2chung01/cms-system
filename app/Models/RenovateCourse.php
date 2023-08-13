<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RenovateCourse extends Model
{

    use HasFactory, SoftDeletes;

    public $table = 'renovate_courses';

    public $fillable = [
        'renovate_courses_uuid',
        'course_categories_uuid',
        'title',
        'name',
        'code',
        'short_desc',
        'description',
        'location',
        'url',
        'date',
        'price',
        'discounted_price',
        'instructor_name',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'renovate_courses_uuid' => 'string',
        'course_categories_uuid' => 'string',
        'title' => 'string',
        'name' => 'string',
        'code' => 'string',
        'short_desc' => 'string',
        'description' => 'string',
        'location' => 'string',
        'url' => 'string',
        'date' => 'string',
        'price' => 'float',
        'discounted_price' => 'float',
        'instructor_name' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'renovate_courses_uuid' => 'required|string',
        'course_categories_uuid' => 'required|string',
        'title' => 'required|string',
        'name' => 'required|string',
        'code' => 'required|string',
        'short_desc' => 'required|string',
        'description' => 'required|string',
        'location' => 'required|string',
        'url' => 'nullable|string',
        'date' => 'nullable|string',
        'price' => 'nullable|numeric|min:0|max:2147483647',
        'discounted_price' => 'nullable|numeric|min:0|max:2147483647',
        'instructor_name' => 'nullable|string',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    public function assets()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'renovate_courses_uuid');
    }

    public function category()
    {
        return $this->belongsTo(RenovateCourseCategory::class, 'course_categories_uuid', 'course_categories_uuid');
    }

    // public function displayDate(): Attribute
    // {
    //     $dateFormatted = Carbon::parse($this->date);

    //     if (isset($this->date)){
    //         return Attribute::make(
    //             get:fn() => $dateFormatted->format('m月d日 ('.$dateFormatted->locale('zh_TW')->minDayName.')'),
    //         );
    //     }
    //     else {
    //         return Attribute::make(
    //             get:fn() => null,
    //         );
    //     }
    // }

}

<?php

namespace App\Repositories;

use App\Models\RenovateCourseCategory;
use App\Repositories\BaseRepository;

class RenovateCourseCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
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

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return RenovateCourseCategory::class;
    }
}

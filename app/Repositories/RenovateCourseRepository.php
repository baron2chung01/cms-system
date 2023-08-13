<?php

namespace App\Repositories;

use App\Models\RenovateCourse;
use App\Repositories\BaseRepository;

class RenovateCourseRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'renovate_courses_uuid',
        'title',
        'name',
        'code',
        'short_desc',
        'description',
        'location',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return RenovateCourse::class;
    }
}

<?php

namespace App\Repositories;

use App\Models\RenovateCases;
use App\Repositories\BaseRepository;

class RenovateCasesRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'renovate_cases_uuid',
        'title',
        'name',
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
        return RenovateCases::class;
    }
}

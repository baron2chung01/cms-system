<?php

namespace App\Repositories;

use App\Models\MaterialsCategory;
use App\Repositories\BaseRepository;

class MaterialsCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'materials_categories_uuid',
        'parents_uuid',
        'code',
        'name',
        'status',
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
        return MaterialsCategory::class;
    }
}

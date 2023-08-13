<?php

namespace App\Repositories;

use App\Models\Materials;
use App\Repositories\BaseRepository;

class MaterialsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'materials_uuid',
        'materials_categories_uuid',
        'name',
        'code',
        'price',
        'group_price',
        'group_qty',
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
        return Materials::class;
    }
}

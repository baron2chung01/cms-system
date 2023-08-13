<?php

namespace App\Repositories;

use App\Models\Region;
use App\Repositories\BaseRepository;

class RegionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'regions_uuid',
        'name',
        'code',
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
        return Region::class;
    }
}

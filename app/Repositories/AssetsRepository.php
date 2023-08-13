<?php

namespace App\Repositories;

use App\Models\Assets;
use App\Repositories\BaseRepository;

class AssetsRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'assets_uuid',
        'module_uuid',
        'resource_path',
        'file_name',
        'type',
        'file_size',
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
        return Assets::class;
    }
}

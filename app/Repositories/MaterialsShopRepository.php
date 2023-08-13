<?php

namespace App\Repositories;

use App\Models\MaterialsShop;
use App\Repositories\BaseRepository;

class MaterialsShopRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'materials_uuid',
        'shops_uuid',
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
        return MaterialsShop::class;
    }
}

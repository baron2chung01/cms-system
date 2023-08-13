<?php

namespace App\Repositories;

use App\Models\ShopCategory;
use App\Repositories\BaseRepository;

class ShopCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shop_categories_uuid',
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
        return ShopCategory::class;
    }
}

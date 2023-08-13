<?php

namespace App\Repositories;

use App\Models\ShopHasRegion;
use App\Repositories\BaseRepository;

class ShopHasRegionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shop_id',
        'region_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ShopHasRegion::class;
    }
}

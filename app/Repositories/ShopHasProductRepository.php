<?php

namespace App\Repositories;

use App\Models\ShopHasProduct;
use App\Repositories\BaseRepository;

class ShopHasProductRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shop_id',
        'product_id',
        'status'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ShopHasProduct::class;
    }
}

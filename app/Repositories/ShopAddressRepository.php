<?php

namespace App\Repositories;

use App\Models\ShopAddress;
use App\Repositories\BaseRepository;

class ShopAddressRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shop_id',
        'address'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ShopAddress::class;
    }
}

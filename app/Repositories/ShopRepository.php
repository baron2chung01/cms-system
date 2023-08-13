<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Repositories\BaseRepository;

class ShopRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shops_uuid',
        'regions_uuid',
        'name',
        'shops_code',
        'phone',
        'whatsapp',
        'contact_person',
        'address',
        'latitude',
        'longitude',
        'desc',
        'remarks',
        'payment_methods',
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
        return Shop::class;
    }
}

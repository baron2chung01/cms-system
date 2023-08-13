<?php

namespace App\Repositories;

use App\Models\Shop;
use App\Repositories\BaseRepository;

class ShopRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shops_uuid',
        'shop_categories_uuid',
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
        'views',
        'rating',
        'product_desc',
        'services_quality',
        'product_categories',
        'logistic_services',
        'geographical_location',
        'facebook',
        'instagram',
        'status',
        'type',
        'banner_layer',
        'position',  //add
        'created_by',
        'updated_by',
        'deleted_by',
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

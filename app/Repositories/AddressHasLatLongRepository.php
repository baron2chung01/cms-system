<?php

namespace App\Repositories;

use App\Models\AddressHasLatLong;
use App\Repositories\BaseRepository;

class AddressHasLatLongRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'address_id',
        'latitude',
        'longitude'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return AddressHasLatLong::class;
    }
}

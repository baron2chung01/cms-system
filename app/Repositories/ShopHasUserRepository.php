<?php

namespace App\Repositories;

use App\Models\ShopHasUser;
use App\Repositories\BaseRepository;

class ShopHasUserRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shop_id',
        'user_id'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return ShopHasUser::class;
    }
}

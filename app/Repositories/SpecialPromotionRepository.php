<?php

namespace App\Repositories;

use App\Models\SpecialPromotion;
use App\Repositories\BaseRepository;

class SpecialPromotionRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'special_promotion_uuid',
        'code',
        'name',
        'short_description',
        'description',
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
        return SpecialPromotion::class;
    }
}

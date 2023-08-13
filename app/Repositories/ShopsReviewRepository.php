<?php

namespace App\Repositories;

use App\Models\ShopsReview;
use App\Repositories\BaseRepository;

class ShopsReviewRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'shops_reviews_uuid',
        'shops_uuid',
        'comment',
        'rating',
        'product_desc',
        'services_quality',
        'product_categories',
        'logistic_services',
        'geographical_location',
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
        return ShopsReview::class;
    }
}

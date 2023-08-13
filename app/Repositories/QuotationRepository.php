<?php

namespace App\Repositories;

use App\Models\Quotation;
use App\Repositories\BaseRepository;

class QuotationRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'quotation_uuid',
        'total_price',
        'name',
        'date',
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
        return Quotation::class;
    }
}

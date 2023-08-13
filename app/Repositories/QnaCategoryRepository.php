<?php

namespace App\Repositories;

use App\Models\QnaCategory;
use App\Repositories\BaseRepository;

class QnaCategoryRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'categories_uuid',
        'parents_uuid',
        'code',
        'name',
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
        return QnaCategory::class;
    }
}

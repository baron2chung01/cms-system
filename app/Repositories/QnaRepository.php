<?php

namespace App\Repositories;

use App\Models\Qna;
use App\Repositories\BaseRepository;

class QnaRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'qna_uuid',
        'qna_categories_uuid',
        'title',
        'date',
        'short_desc',
        'description',
        'top_blog',
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
        return Qna::class;
    }
}

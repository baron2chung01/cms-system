<?php

namespace App\Repositories;

use App\Models\Article;
use App\Repositories\BaseRepository;

class ArticleRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'articles_uuid',
        'code',
        'name',
        'title',
        'desc',
        'short_desc',
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
        return Article::class;
    }
}

<?php

namespace App\Repositories;

use App\Models\Banner;
use App\Repositories\BaseRepository;

class BannerRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'banner_uuid',
        'banner_module',
        'url',
        'view_count',
        'status',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Banner::class;
    }
}

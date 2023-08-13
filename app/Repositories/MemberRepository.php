<?php

namespace App\Repositories;

use App\Models\Member;
use App\Repositories\BaseRepository;

class MemberRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'name',
        'email',
        'phone',
        'password',
        'status',
        'type',
        'deleted_at'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Member::class;
    }
}

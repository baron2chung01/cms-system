<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    const ACTIVE = 0;
    const INACTIVE = 1;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
    ];

    const MODULES = [
        'home'            => '主頁',
        'blog'            => '專題分享',
        'shop'            => '材料店',
        'renovate_course' => '裝修分科',
    ];

    public $table = 'banners';

    public $fillable = [
        'banner_uuid',
        'banner_module',
        'url',
        'view_count',
        'status',
    ];

    protected $casts = [
        'banner_module' => 'string',
        'view_count'    => 'integer',
        'status'        => 'integer',
    ];

    public static $rules = [
        'banner_uuid'   => 'required|string',
        'banner_module' => 'required|string',
        'url'           => 'nullable',
        'status'        => 'required|integer',
    ];

    public function assets()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'banner_uuid');
    }

    public function displayBannerModule(): Attribute
    {
        return Attribute::make(
            get: fn() => self::MODULES[$this->banner_module],
        );
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get: fn() => self::STATUS[$this->status],
        );
    }

}

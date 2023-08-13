<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCategory extends Model
{
    use SoftDeletes;
    use HasFactory;

    const ACTIVE   = 0;
    const INACTIVE = 1;
    const DRAFT    = 2;
    const DELETE   = 3;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
        self::DRAFT    => 'Draft',
        self::DELETE   => 'Delete',
    ];

    const DISP_STATUS = [
        self::ACTIVE   => '公開',
        self::INACTIVE => '非公開',
        self::DRAFT    => '待處理',
        self::DELETE   => '已刪除',
    ];

    public $table = 'shop_categories';

    public $fillable = [
        'shop_categories_uuid',
        'parents_uuid',
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'code'   => 'string',
        'name'   => 'string',
        'status' => 'integer',
    ];

    public static $rules = [
        'shop_categories_uuid' => 'required|string',
        'parents_uuid'         => 'nullable|string',
        'code'                 => 'nullable|string',
        'name'                 => 'required|string|max:255',
        'status'               => 'required|integer',
        'created_by'           => 'nullable',
        'updated_by'           => 'nullable',
        'deleted_by'           => 'nullable',
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class, 'shop_categories_uuid', 'shop_categories_uuid');
    }

    public function topShops()
    {
        return $this->hasMany(Shop::class, 'shop_categories_uuid', 'shop_categories_uuid')->orderBy('views', 'DESC')->take(12);
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::DISP_STATUS[$this->status],
        );
    }
    public function displayParents(): Attribute
    {
        $nameCollection = self::where('shop_categories_uuid', $this->parents_uuid)->pluck('name');
        if (!$nameCollection->isEmpty()) {
            return Attribute::make(
                get:fn() => $nameCollection[0],
            );
        } else {
            return Attribute::make(
                get:fn() => null,
            );

        }

    }
    public function parentDetail(): Attribute
    {
        $nameCollection = self::where('shop_categories_uuid', $this->parents_uuid)->get(['id', 'shop_categories_uuid', 'name']);
        if (!$nameCollection->isEmpty()) {
            return Attribute::make(
                get:fn() => $nameCollection[0],
            );
        } else {
            return Attribute::make(
                get:fn() => null,
            );

        }

    }

}

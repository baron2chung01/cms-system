<?php

namespace App\Models;

use App\Models\Materials;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialsShop extends Model
{
    use HasFactory;
    use SoftDeletes;

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

    public $table = 'materials_shops';

    public $fillable = [
        'materials_shops_uuid',
        'materials_uuid',
        'shops_uuid',
        'group_price',
        'group_qty',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'materials_shops_uuid' => 'string',
        'shops_uuid'           => 'string',
        'materials_uuid'       => 'string',
        'created_by'           => 'string',
        'updated_by'           => 'string',
        'deleted_by'           => 'string',
        'group_price'          => 'float',
        'group_qty'            => 'integer',
        'status'               => 'integer',
    ];

    public static $rules = [
        'materials_shops_uuid' => 'required|string',
        'materials_uuid'       => 'required|string',
        'shops_uuid'           => 'required|string',
        'group_price'          => 'nullable|numeric',
        'group_qty'            => 'nullable|integer',
        'status'               => 'nullable',
        'created_by'           => 'nullable|string',
        'updated_by'           => 'nullable|string',
        'deleted_by'           => 'nullable|string',
        'created_at'           => 'nullable',
        'updated_at'           => 'nullable',
        'deleted_at'           => 'nullable',
    ];

    public function materials()
    {
        return $this->belongsTo(Materials::class, 'materials_uuid', 'materials_uuid');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shops_uuid', 'shops_uuid')
            ->select('shops_uuid', 'regions_uuid', 'name', 'shops_code', 'desc', 'remarks');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }
}
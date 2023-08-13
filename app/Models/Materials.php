<?php

namespace App\Models;

use App\Models\MaterialsCategory;
use App\Models\MaterialsShop;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materials extends Model
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

    public $table = 'materials';

    public $fillable = [
        'materials_uuid',
        'materials_categories_uuid',
        'name',
        'code',
        'price',
        // 'group_price',
        // 'group_qty',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'materials_uuid'            => 'string',
        'materials_categories_uuid' => 'string',
        'name'                      => 'string',
        'code'                      => 'string',
        'created_by'                => 'string',
        'updated_by'                => 'string',
        'deleted_by'                => 'string',
    ];

    public static $rules = [
        'materials_uuid'            => 'required|string',
        'materials_categories_uuid' => 'nullable|string',
        'name'                      => "required|string",
        'code'                      => 'required|string',
        'price'                     => 'required|numeric',
        // 'group_price'               => 'nullable',
        // 'group_qty'                 => 'nullable',
        'status'                    => 'nullable',
        'created_by'                => 'nullable|string',
        'updated_by'                => 'nullable|string',
        'deleted_by'                => 'nullable|string',
        'created_at'                => 'nullable',
        'updated_at'                => 'nullable',
        'deleted_at'                => 'nullable',
    ];

    public function category()
    {
        return $this->belongsTo(MaterialsCategory::class, 'materials_categories_uuid', 'materials_categories_uuid');
    }

    public function shops_info()
    {
        return $this->hasMany(MaterialsShop::class, 'materials_uuid', 'materials_uuid');
    }

    public function shops()
    {
        return $this->hasManyThrough(
            'App\Models\Shop', // related model
            'App\Models\MaterialsShop', // pivot model
            'materials_uuid', // current model id in pivot
            'shops_uuid', // id of related model
            'materials_uuid', // id of current model
            'shops_uuid', // related model id in pivot
        )->select('shops.shops_uuid', 'shops.regions_uuid', 'shops.name', 'shops.shops_code',
            'shops.desc', 'shops.remarks');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

}
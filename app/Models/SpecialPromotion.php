<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SpecialPromotion extends Model
{
    use SoftDeletes;
    use HasFactory;

    const ACTIVE   = 0;
    const INACTIVE = 1;
    const DELETE   = 2;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
        self::DELETE   => 'Delete',
    ];

    const BANNER  = 0;
    const RECEIPT = 1;

    const ASSET = [
        self::BANNER  => 'Banner',
        self::RECEIPT => 'Receipt',
    ];

    public $table = 'special_promotions';

    public $fillable = [
        'special_promotion_uuid',
        'code',
        'value',
        'name',
        'short_description',
        'description',
        'url',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'special_promotion_uuid' => 'string',
        'code'                   => 'string',
        'value' => 'float',
        'name'                   => 'string',
        'short_description'      => 'string',
        'description'            => 'string',
        'url'                    => 'string',
        'created_by'             => 'string',
        'updated_by'             => 'string',
        'deleted_by'             => 'string',
    ];

    public static $rules = [
        'special_promotion_uuid' => 'required|string',
        'code'                   => 'required|string',
        'value'                   => 'nullable|numeric',
        'name'                   => 'required|string',
        'short_description'      => 'nullable',
        'description'            => 'required',
        'url'                    => 'nullable|string',
        'status'                 => 'nullable',
        'created_by'             => 'nullable|string',
        'updated_by'             => 'nullable|string',
        'deleted_by'             => 'nullable|string',
        'created_at'             => 'nullable',
        'updated_at'             => 'nullable',
        'deleted_at'             => 'nullable',
    ];

    public function image()
    {
        return $this->hasOne(Assets::class, 'module_uuid', 'special_promotion_uuid')->where('module_type', self::BANNER)->oldest();
    }

    public function receipts()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'special_promotion_uuid')->where('module_type', self::RECEIPT)->where('status', ASSETS::ACTIVE);
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shops_has_special_promotions');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

    public function displayName(): Attribute
    {
        return Attribute::make(
            get:fn() => $this->name . ' (' . $this->code . ')',
        );
    }

}

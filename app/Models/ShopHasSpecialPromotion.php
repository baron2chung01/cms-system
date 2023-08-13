<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopHasSpecialPromotion extends Model
{
    use SoftDeletes;
    public $table = 'shops_has_special_promotions';

    const ACTIVE   = 0;
    const INACTIVE = 1;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
    ];

    public $fillable = [
        'shop_id',
        'special_promotion_id',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public static $rules = [
        'shop_id'              => 'required',
        'special_promotion_id' => 'required',
        'status'               => 'required',
    ];

}

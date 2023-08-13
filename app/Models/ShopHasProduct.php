<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopHasProduct extends Model
{
    use SoftDeletes;

    const ACTIVE = 0;
    const INACTIVE = 1;
    const DRAFT = 2;
    const DELETE = 3;

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

    public $table = 'shops_has_products';

    public $fillable = [
        'id',
        'shop_id',
        'product_id',
        'status',
    ];

    protected $casts = [

    ];

    public static $rules = [
        'shop_id'    => 'required|integer',
        'product_id' => 'required|integer',
        'status'     => 'required|integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id')->withTrashed();
    }
}

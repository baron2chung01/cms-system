<?php

namespace App\Models;

use App\Models\MaterialsShop;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shop extends Model
{
    use HasFactory;
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

    const PRODUCT = 0;
    const DECOR = 1;
    const TYPE = [
        self::PRODUCT => '產品',
        self::DECOR   => '裝修',
    ];

    const PAYMENT = [
        'Cash',
        'VISA',
        'FPS',
        'MasterCard',
        'AECard',
        'PayMe',
        'Alipay',
        'WeChatPay',
        '八達通',
        'TapNGo',
        'BoCPay',
        'Cheque',
    ];

    const BANNER = [
        'max' => 1,
    ];

    public $table = 'shops';

    public $fillable = [
        'shops_uuid',
        'shop_categories_uuid',
        'name',
        'shops_code',
        'phone',
        'whatsapp',
        'contact_person',
        'latitude',
        'longitude',
        'desc',
        'remarks',
        'payment_methods',
        'views',
        'rating',
        'product_desc',
        'services_quality',
        'product_categories',
        'logistic_services',
        'geographical_location',
        'facebook',
        'instagram',
        'status',
        'type',
        'banner_layer',
        'position', // add
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'shops_uuid'           => 'string',
        'shop_categories_uuid' => 'string',
        'name'                 => 'string',
        'shops_code'           => 'string',
        'phone'                => 'string',
        'whatsapp'             => 'string',
        'contact_person'       => 'string',
        'desc'                 => 'string',
        'remarks'              => 'string',
        'payment_methods'      => 'string',
        'facebook'             => 'string',
        'instagram'            => 'string',
        'created_by'           => 'string',
        'updated_by'           => 'string',
        'deleted_by'           => 'string',
    ];

    public static $rules = [
        'shops_uuid'            => 'required|string',
        'users_uuid'            => 'nullable|string',
        'shop_categories_uuid'  => 'required|string',
        'name'                  => 'required|string',
        'shops_code'            => 'required|string',
        'phone'                 => 'required|string',
        'whatsapp'              => 'nullable|string',
        'contact_person'        => 'required|string',
        'desc'                  => 'required|string',
        'remarks'               => 'nullable|string',
        'payment_methods'       => 'required|string',
        'views'                 => 'nullable|integer',
        'rating'                => 'nullable|numeric|between:0,5',
        'product_desc'          => 'nullable|numeric|between:0,5',
        'services_quality'      => 'nullable|numeric|between:0,5',
        'product_categories'    => 'nullable|numeric|between:0,5',
        'logistic_services'     => 'nullable|numeric|between:0,5',
        'geographical_location' => 'nullable|numeric|between:0,5',
        'facebook'              => 'nullable|string',
        'instagram'             => 'nullable|string',
        'status'                => 'required|integer',
        'type'                  => 'required|integer',
        'position'              => 'nullable|integer', // add
        'created_by' => 'nullable|string',
        'updated_by'            => 'nullable|string',
        'deleted_by'            => 'nullable|string',
        'created_at'            => 'nullable',
        'updated_at'            => 'nullable',
        'deleted_at'            => 'nullable',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'shops_has_users', 'shop_id', 'user_id');
    }

    public function addresses()
    {
        return $this->hasMany(ShopAddress::class, 'shop_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(ShopCategory::class, 'shop_categories_uuid', 'shop_categories_uuid');
    }

    public function reviews()
    {
        return $this->hasMany(ShopsReview::class, 'shops_uuid', 'shops_uuid');
    }

    public function assets()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'shops_uuid');
    }

    public function banner()
    {
        return $this->hasOne(Assets::class, 'module_uuid', 'shops_uuid')->where('type', 'banner');
    }

    public function regions()
    {
        return $this->belongsToMany(Region::class, 'shop_has_regions', 'shop_id', 'region_id');
    }

    public function materials_info()
    {
        return $this->hasMany(MaterialsShop::class, 'shops_uuid', 'shops_uuid');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'shops_has_products', 'shop_id', 'product_id')->withPivot(['status'])->orderByRaw('main_product IS NULL ASC, main_product ASC');
    }

    public function activeProducts()
    {
        return $this->belongsToMany(Product::class, 'shops_has_products', 'shop_id', 'product_id')->wherePivot('status', ShopHasProduct::ACTIVE)->orderByRaw('main_product IS NULL ASC, main_product ASC');
    }

    public function specialPromotions()
    {
        return $this->belongsToMany(SpecialPromotion::class, 'shops_has_special_promotions');
    }

    public function receipts()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'shops_uuid')->where('module_type', SpecialPromotion::RECEIPT);
    }

    public function displayViews(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->views >= 1000 && $this->views < 1000000) {
                    // If the number is between 1000 and 999,999, format it as X.XK
                    $formattedNumber = number_format($this->views / 1000, 1) . 'K+';
                } else if ($this->views >= 1000000) {
                    // If the number is greater than or equal to 1,000,000, format it as X.XM
                    $formattedNumber = number_format($this->views / 1000000, 1) . 'M+';
                } else {
                    // If the number is less than 1000, use the regular number format
                    $formattedNumber = number_format($this->views);
                }
                return $formattedNumber;
            },
        );
    }
}

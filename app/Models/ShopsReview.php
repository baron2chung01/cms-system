<?php

namespace App\Models;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopsReview extends Model
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

    public $table = 'shops_reviews';

    public $fillable = [
        'shops_reviews_uuid',
        'shops_uuid',
        'comment',
        'rating',
        'product_desc',
        'services_quality',
        'product_categories',
        'logistic_services',
        'geographical_location',
        'status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'shops_reviews_uuid' => 'string',
        'shops_uuid'         => 'string',
        'comment'            => 'string',
        'created_by'         => 'string',
        'updated_by'         => 'string',
        'deleted_by'         => 'string',
    ];

    public static $rules = [
        'shops_reviews_uuid'    => 'required|string',
        'shops_uuid'            => 'required|string',
        'comment'               => 'required|string',
        'rating'                => 'required',
        'product_desc'          => 'required',
        'services_quality'      => 'required',
        'product_categories'    => 'required',
        'logistic_services'     => 'required',
        'geographical_location' => 'required',
        'status'                => 'required',
        'created_by'            => 'nullable|string',
        'updated_by'            => 'nullable|string',
        'deleted_by'            => 'nullable|string',
        'created_at'            => 'nullable',
        'updated_at'            => 'nullable',
        'deleted_at'            => 'nullable',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shops_uuid', 'shops_uuid');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'clients_uuid', 'created_by');
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::DISP_STATUS[$this->status],
        );
    }

    public function displayRating(): Attribute
    {
        return Attribute::make(
            get:fn() => round($this->rating, 2),
        );
    }
}

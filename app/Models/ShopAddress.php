<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopAddress extends Model
{
    use SoftDeletes;public $table = 'shop_addresses';

    public $fillable = [
        'shop_id',
        'address',
    ];

    protected $casts = [

    ];

    public static $rules = [
        'shop_id' => 'required|integer',
        'address' => 'required|string',
    ];

    public function latlong()
    {
        return $this->hasOne(AddressHasLatLong::class, 'address_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

}

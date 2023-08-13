<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AddressHasLatLong extends Model
{
    use SoftDeletes;public $table = 'address_has_lat_longs';

    public $fillable = [
        'address_id',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'latitude'  => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public static $rules = [
        'address_id' => 'required|integer',
        'latitude'   => 'required',
        'longitude'  => 'required',
    ];

    public function address()
    {
        return $this->belongsTo(ShopAddress::class, 'shop_id');
    }

}

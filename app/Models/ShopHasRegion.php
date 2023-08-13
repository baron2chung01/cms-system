<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;;
class ShopHasRegion extends Model
{
     use SoftDeletes;    public $table = 'shop_has_regions';

    public $fillable = [
        'shop_id',
        'region_id'
    ];

    protected $casts = [
        
    ];

    public static $rules = [
        'shop_id' => 'required|integer',
        'region_id' => 'required|integer'
    ];

    
}

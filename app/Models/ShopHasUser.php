<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\SoftDeletes;;
class ShopHasUser extends Model
{
     use SoftDeletes;    public $table = 'shops_has_users';

    public $fillable = [
        'shop_id',
        'user_id'
    ];

    protected $casts = [

    ];

    public static $rules = [
        'shop_id' => 'required|integer',
        'user_id' => 'required|integer'
    ];


}

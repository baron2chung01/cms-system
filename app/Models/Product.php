<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    public $table = 'products';

    public $fillable = [
        'id',
        'product_uuid',
        'name',
        'qty',
        'price',
        'description',
        'main_product',
    ];

    protected $casts = [
        'product_uuid' => 'string',
        'name'         => 'string',
        'qty'          => 'integer',
        'price'        => 'float',
    ];

    public static $rules = [
        'product_uuid' => 'required',
        'name'         => 'required|string',
        'qty'          => 'nullable|integer',
        'price'        => 'nullable|numeric',
        'description'  => 'nullable|string',
        'main_product' => 'required',
    ];

    public function images()
    {
        return $this->hasMany(Assets::class, 'module_uuid', 'product_uuid');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    public $table = 'shops';

    public $fillable = [
        'shops_uuid',
        'regions_uuid',
        'name',
        'shops_code',
        'phone',
        'whatsapp',
        'contact_person',
        'address',
        'latitude',
        'longitude',
        'desc',
        'remarks',
        'payment_methods',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'shops_uuid' => 'string',
        'regions_uuid' => 'string',
        'name' => 'string',
        'shops_code' => 'string',
        'phone' => 'string',
        'whatsapp' => 'string',
        'contact_person' => 'string',
        'address' => 'string',
        'desc' => 'string',
        'remarks' => 'string',
        'payment_methods' => 'string',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'shops_uuid' => 'required|string',
        'regions_uuid' => 'required|string',
        'name' => 'required|string',
        'shops_code' => 'required|string',
        'phone' => 'required|string',
        'whatsapp' => 'required|string',
        'contact_person' => 'required|string',
        'address' => 'required|string',
        'latitude' => 'required',
        'longitude' => 'required',
        'desc' => 'required|string',
        'remarks' => 'required|string',
        'payment_methods' => 'required|string',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $table = 'clients';

    public $fillable = [
        'clients_uuid',
        'first_name',
        'last_name',
        'username',
        'password',
        'email',
        'address',
        'phone',
        'subscription',
        'points',
        'status',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'clients_uuid' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'username' => 'string',
        'password' => 'string',
        'email' => 'string',
        'address' => 'string',
        'phone' => 'string',
        'subscription' => 'boolean',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'clients_uuid' => 'required|string',
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'username' => 'required|string',
        'password' => 'required|string',
        'email' => 'required|string',
        'address' => 'nullable|string',
        'phone' => 'nullable|string',
        'subscription' => 'required|boolean',
        'points' => 'nullable',
        'status' => 'required',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];

    
}

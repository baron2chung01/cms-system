<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasFactory, SoftDeletes, HasApiTokens;

    const ACTIVE   = 0;
    const INACTIVE = 1;
    const DELETE   = 2;

    const STATUS = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
        self::DELETE   => 'Delete',
    ];

    const SUBSCRIPTION = [
        0 => 'Subscribed',
        1 => 'Not Subscribed',
    ];

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
        'deleted_by',
    ];

    protected $casts = [
        'clients_uuid' => 'string',
        'first_name'   => 'string',
        'last_name'    => 'string',
        'username'     => 'string',
        'password'     => 'string',
        'email'        => 'string',
        'address'      => 'string',
        'phone'        => 'string',
        'subscription' => 'boolean',
        'created_by'   => 'string',
        'updated_by'   => 'string',
        'deleted_by'   => 'string',
    ];

    public static $rules = [
        'clients_uuid' => 'nullable|string',
        'first_name'   => 'required|string',
        'last_name'    => 'required|string',
        'username'     => 'required|string',
        'password'     => 'required|string|min:8|max:255',
        'email'        => 'required|string',
        'address'      => 'nullable|string',
        'phone'        => 'required|string',
        'subscription' => 'required|boolean',
        'points'       => 'between:0,2147483647|numeric|nullable',
        'status'       => 'required',
        'created_by'   => 'nullable|string',
        'updated_by'   => 'nullable|string',
        'deleted_by'   => 'nullable|string',
        'created_at'   => 'nullable',
        'updated_at'   => 'nullable',
        'deleted_at'   => 'nullable',
    ];

    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'clients_uuid', 'clients_uuid');
    }

    public function image()
    {
        return $this->hasOne(Assets::class, 'module_uuid', 'clients_uuid')->oldest();
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

    public function displaySubscription(): Attribute
    {
        return Attribute::make(
            get:fn() => self::SUBSCRIPTION[$this->subscription],
        );
    }
}

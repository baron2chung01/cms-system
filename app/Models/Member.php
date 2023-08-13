<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Member extends Authenticatable
{
    use SoftDeletes;
    use HasFactory;
    use HasApiTokens;
    use Notifiable;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // status to be changed
    const ACTIVE   = 0;
    const INACTIVE = 1;
    const DELETE = 2;
    const STATUS   = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
        self::DELETE => 'Delete',
    ];

    const CLIENT   = 0;
    const BUSINESS = 1;
    const TYPE     = [
        self::CLIENT   => 'Client',
        self::BUSINESS => 'Business',
    ];

    public $table = 'members';

    public $fillable = [
        'id',
        'members_uuid',
        'clients_uuid',
        'name',
        'email',
        'phone',
        'password',
        'status',
        'type',
        'remember_token',
        'deleted_at',
    ];

    protected $casts = [
        'members_uuid'   => 'string',
        'clients_uuid'   => 'string',
        'name'           => 'string',
        'email'          => 'string',
        'phone'          => 'integer',
        'password'       => 'string',
        'status'         => 'integer',
        'type'           => 'integer',
        'remember_token' => 'string',
    ];

    public static $rules = [
        'members_uuid'   => 'required|string',
        'clients_uuid'   => 'nullable|string',
        'name'           => 'required|string|max:255',
        'email'          => 'required|string|max:255',
        'phone'          => 'required|integer|between:10000000,99999999',
        'password'       => 'required|string|min:8|max:255',
        'status'         => 'required|integer',
        'type'           => 'required|integer',
        'remember_token' => 'nullable|string|max:100',
        'created_at'     => 'nullable',
        'updated_at'     => 'nullable',
        'deleted_at'     => 'nullable',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'clients_uuid', 'clients_uuid');
    }

    public function findForPassport($username)
    {
        return $this->where('email', $username)->first();
    }

    public function displayStatus(): Attribute
    {
        return Attribute::make(
            get:fn() => self::STATUS[$this->status],
        );
    }

    public function displayType(): Attribute
    {
        return Attribute::make(
            get:fn() => self::TYPE[$this->type],
        );
    }

    public function pointHistories()
    {
        return $this->hasMany(Point::class, 'members_uuid', 'members_uuid')->orderBy('created_at', 'DESC');
    }
}

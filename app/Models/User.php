<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    const OWNER = 0;
    const PARTNER = 1;

    const ROLE = [
        self::OWNER => "Owner",
        self::PARTNER => "Partner",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'users_uuid',
        'role',
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static $rules = [
        'users_uuid'     => 'required|string',
        'role'           => 'required|integer',
        'name'           => 'required|string|max:255',
        'email'          => 'required|string|max:255',
        'password'       => 'required|string|min:8|max:255',
        'remember_token' => 'nullable|string|max:100',
        'created_at'     => 'nullable',
        'updated_at'     => 'nullable',
        'deleted_at'     => 'nullable',
    ];

    public function displayName(): Attribute
    {
        return Attribute::make(
            get:fn() => $this->name . ' (UUID: ' . $this->users_uuid . ')',
        );
    }

    public function shops()
    {
        return $this->belongsToMany(Shop::class, 'shops_has_users', 'user_id', 'shop_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Point
 * @package App\Models
 * @version October 18, 2022, 11:51 pm HKT
 *
 * @property integer $member_id
 * @property integer $type
 * @property number $amount
 * @property string $remark
 * @property string $image
 */
class Point extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'points';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $dates = ['deleted_at'];

    const MINUS = 0;
    const ADD   = 1;
    const TYPE  = [
        self::MINUS => '減',
        self::ADD   => '加',
    ];

    public $fillable = [
        'members_uuid',
        'type', // add 1/minus 0
        'created_by',
        'updated_by',
        'amount', // positive number
        'remark',
        'created_at',
        'expired_at',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'          => 'integer',
        'member_uuid' => 'string',
        'type'        => 'integer',
        'amount'      => 'float',
        'remark'      => 'string',
        'created_by'  => 'string',
        'updated_by'  => 'string',
        'expired_at'  => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'member_uuid' => 'required',
        'type'        => 'required|integer',
        'amount'      => 'required|numeric|between:-2147483646,2147483646',
        'created_by'  => 'nullable',
        'updated_by'  => 'nullable',
        'created_at'  => 'nullable',
        'updated_at'  => 'nullable',
        'expired_at'  => 'nullable',
        'remark'      => 'nullable|string|max:255',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class, 'members_uuid', 'members_uuid');
    }

    public function displayType(): Attribute
    {
        return Attribute::make(
            get:fn() => self::TYPE[$this->type],
        );
    }
}

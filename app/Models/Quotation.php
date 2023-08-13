<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    public $table = 'quotations';

    public $fillable = [
        'quotation_uuid',
        'total_price',
        'name',
        'date',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'quotation_uuid' => 'string',
        'total_price' => 'string',
        'name' => 'string',
        'date' => 'date',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static $rules = [
        'quotation_uuid' => 'required|string',
        'total_price' => 'required|string',
        'name' => 'required|string',
        'date' => 'required',
        'created_by' => 'nullable|string',
        'updated_by' => 'nullable|string',
        'deleted_by' => 'nullable|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable',
        'deleted_at' => 'nullable'
    ];


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'tran_id',
        'tran_type',
        'product_id',
        'qty',
        'price',
        'detail',
    ];
}

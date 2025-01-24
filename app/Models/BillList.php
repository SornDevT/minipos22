<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillList extends Model
{
    //
    protected $fillable = [
        'bill_id',
        'name',
        'qty',
        'price'
    ];
}

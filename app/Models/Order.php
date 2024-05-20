<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'vin_number',
        'year',
        'make',
        'model',
        'dealership_name',
        'stock_number',
        'seller_contact',
        'address_permanent',
        'date',
        'note',
        'history_report',
        'assessment_report',
    ];
}

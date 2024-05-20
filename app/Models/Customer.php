<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_type',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'password',
        'work_with_other_inspection_companies',
        'interested_in_doing_mobile_mechanic_work',
        'ase',
        'education',
        'work_experience',
    ];
}

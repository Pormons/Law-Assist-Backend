<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    use HasFactory;

    protected $fillable =[
        'fullname',
        'address',
        'roll_signed_date',
        'roll_number',
        'phone_number',
        'email',
    ];

    protected $table = 'lawyer';
}

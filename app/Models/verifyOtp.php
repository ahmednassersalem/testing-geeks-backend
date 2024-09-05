<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class verifyOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'email',
        'role',
        'expired_date',
    ];
}

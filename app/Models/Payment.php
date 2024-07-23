<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'id',
        'payment_id',
        'payer_id',
        'payer_name',
        'payer_email',
        'amount',
        'payment_status'
    ];
}

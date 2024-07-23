<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Specify the fillable fields if needed
    protected $fillable = [
        'user_id', 
        'suppliesId', 
        'order_id',
        'quantity', 
        'price', 
        'status'];


        public function product()
        {
            return $this->belongsTo(Supplies::class, 'suppliesId');
        }
        
        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }
}

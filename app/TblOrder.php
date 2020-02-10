<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblOrder extends Model
{
    protected $fillable = [
        'password',
        'customer_id', 
        'customer_status', 
        'subtotal', 
        'discount', 
        'tax_id', 
        'total', 
        'note', 
        'order_date', 
        'cancel_date',
        'is_deleted',
        'order_serial_id',
        'menu_id',
        'order_route'
    ];    
}

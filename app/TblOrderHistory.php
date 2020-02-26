<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblOrderHistory extends Model
{
    protected $fillable = [
        'clinic_id',
        'staff_id', 
        'rank_id', 
        'order_id', 
        'menu_id', 
        'interviewer_id', 
        'interview_start', 
        'interview_end', 
        'amount', 
        'discount', 
        'status', 
        'order_route',         
        'staff_choosed',
        'is_deleted',
        'rank_schedule_id',
        'order_type',
        'order_date',
        'customer_id',
        'note',
        'order_serial_id',
    ];    
}

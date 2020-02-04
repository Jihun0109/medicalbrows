<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblOrder extends Model
{
    protected $fillable = [
        'is_new',
        'phone_number', 
        'order_id', 
        'staff_id', 
        'counselor_id', 
        'menu_id', 
        'clinic_id', 
        'start_time', 
        'end_time', 
        'description',
        'is_deleted'];    
}

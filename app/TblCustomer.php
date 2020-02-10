<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblCustomer extends Model
{
    protected $fillable = [
        'email',
        'gender', 
        'first_name', 
        'last_name', 
        'address', 
        'phonenumber', 
        'birthday', 
        'is_deleted',         
    ];  
}

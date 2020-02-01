<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblTaxRate extends Model
{  
    protected $fillable = ['name','amount', 'start_time', 'end_time', 'is_deleted'];    
}

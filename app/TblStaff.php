<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TblStaff extends Model
{
    protected $table = 'tbl_staffs';
    protected $fillable = ['full_name', 'staff_type_id', 'clinic_id','is_vacation'];
}

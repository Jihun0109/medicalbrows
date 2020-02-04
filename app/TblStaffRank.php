<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblStaffRank extends Model
{
    protected $fillable = ['rank_id','staff_id','promo_date','is_deleted'];    
}

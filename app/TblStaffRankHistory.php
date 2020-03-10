<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblStaffRankHistory extends Model
{
    protected $fillable = ['rank_id','staff_id','part_id','promo_date','is_deleted','unique_id'];
}

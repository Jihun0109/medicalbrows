<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblShiftHistory extends Model
{
    protected $fillable = ["staff_id","rank_id","date","status"];
}

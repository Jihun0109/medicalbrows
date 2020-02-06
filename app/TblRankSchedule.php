<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblRankSchedule extends Model
{
    protected $fillable = ['rank_id','part_id', 'start_time', 'end_time', 'is_deleted'];    
}

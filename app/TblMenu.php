<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblMenu extends Model
{
    protected $fillable = ['name','rank_id','tax_id','amount', 'start_time', 'end_time', 'is_deleted', 'code'];
}

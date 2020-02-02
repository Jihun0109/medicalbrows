<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class TblClinic extends Model
{
    protected $fillable = ['name','address','email','is_vacation'];
}

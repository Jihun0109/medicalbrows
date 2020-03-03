<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TblLog;

class LogsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $keyword = \Request::get('keyword');
        return TblLog::where(function($query) use ($keyword){
                                    $query->where('log','LIKE',"%".$keyword."%")->
                                            orWhere('created_at','LIKE BINARY',"%".$keyword."%")->
                                            orWhere('updated_at','LIKE BINARY',"%".$keyword."%");                                            
                              })->latest()->get();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TblRank;

class ApiController extends Controller
{
    // Call to this function to render data to a specified json format
    function makeFormattedJson($dataList, $code = 20000)
    {        
        return array(
            'code' => $code,
            'data' => array(
                'total' => $dataList->count(),
                'items' => $dataList
            )
        );
    }

    public function rankList(Request $request)
    {
        $ranks = TblRank::select('id','name')->get();
        return $this->makeFormattedJson($ranks);
    }

    public function rankAdd(Request $request)
    {

    }

    public function rankRemove(Request $request)
    {

    }


}

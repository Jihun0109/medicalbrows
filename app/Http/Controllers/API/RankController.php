<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\TblRank;

class RankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword = \Request::get('keyword');
        if ($keyword){
            return TblRank::where('is_deleted', 0)->
                              where(function($query) use ($keyword){
                                    $query->where('name','LIKE',"%".$keyword."%")->
                                            orWhere('short_name','LIKE',"%".$keyword."%");
                              })->latest()->get();
        }

        return TblRank::where('is_deleted', 0)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $this->validate($request, [
            'name' => ['required',
                            Rule::unique('tbl_ranks')->where(function ($query){
                                return $query->where('is_deleted','0');
                            })
                        ],
            'short_name' => ['required',
                            Rule::unique('tbl_ranks')->where(function ($query){
                                return $query->where('is_deleted','0');
                            })
                        ],
        ]);

        $duplicateRank = TblRank::where('is_deleted','1')->where(function($query) use ($request) {
            return $query->where('name',$request->name)->orWhere('short_name',$request->short_name);
        })->first();

        if ($duplicateRank){
            $duplicateRank->name = $request->name;
            $duplicateRank->short_name = $request->short_name;
            $duplicateRank->is_deleted = 0;
            $duplicateRank->save();
            return ['result'=>'success', 'message'=>'Data created successfully'];
        } else
            return TblRank::create(['name' => $request->name, 'short_name' => $request->short_name]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => ['required',
                            Rule::unique('tbl_ranks')->where(function ($query) use ($request){
                                return $query->where('is_deleted','0')->where('id',"<>", $request->id);
                            })
                        ],
            'short_name' => ['required',
                            Rule::unique('tbl_ranks')->where(function ($query) use ($request){
                                return $query->where('is_deleted','0')->where('id',"<>", $request->id);
                            })
                        ],
        ]);
        $rank = TblRank::findOrFail($id);
        $rank->update($request->all());
        return $id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rank = TblRank::findOrFail($id);
        
        $rank->is_deleted = 1;
        $rank->save();
        return ['message' => 'Rank deleted'];
        
    }
}

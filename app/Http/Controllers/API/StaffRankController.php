<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaffRank;
use Carbon\Carbon;
use DB;
use Log;
use Illuminate\Validation\Rule;



class StaffRankController extends Controller
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
            return DB::table('tbl_staff_ranks')->
                join('tbl_staffs','tbl_staffs.id','tbl_staff_ranks.staff_id')->
                join('tbl_ranks','tbl_ranks.id','tbl_staff_ranks.rank_id')->
                select('tbl_staff_ranks.*')->
                where('tbl_staff_ranks.is_deleted', 0)->
                where(function($query) use ($keyword){
                    $query->where('tbl_staffs.full_name','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_ranks.id','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_ranks.promo_date','LIKE BINARY',"%".$keyword."%")->
                            orWhere('tbl_staffs.alias','LIKE',"%".$keyword."%")->
                            orWhere('tbl_ranks.name','LIKE',"%".$keyword."%");
              })->latest()->get();
        }
        return TblStaffRank::where('is_deleted', 0)->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info($request);
        $this->validate($request, [
            'rank_id' => 'required',
            'staff_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query){
                                return $query->where('is_deleted','0');
                            })
                        ],
            'part_id' => 'required',
        ]);
        return TblStaffRank::create([
            'rank_id' => $request->rank_id,
            'staff_id' => $request->staff_id,
            'part_id' => $request->part_id,
            'promo_date' => Carbon::parse($request->promo_date)->format('Y-m-d'),
        ]);
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
        Log::info($request);
        $this->validate($request, [
            'rank_id' => 'required',
            'staff_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query) use ($request) {
                                return $query->where('is_deleted','0')->where('id',"<>", $request->id);
                            })
                        ],
            'part_id' => 'required',
        ]);
        $values = $request;
        Log::info($request->promo_date);
        $request['promo_date'] = Carbon::parse($request->promo_date);
        Log::info($values->promo_date);
        $staffrank = TblStaffRank::findOrFail($id);        
        $staffrank->update($values->all());
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
        $staffrank = TblStaffRank::findOrFail($id);
        
        $staffrank->is_deleted = 1;
        $staffrank->save();
        return ['message' => 'staffrank deleted'];
    }
}

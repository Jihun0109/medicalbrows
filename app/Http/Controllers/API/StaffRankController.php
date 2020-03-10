<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaffRank;
use App\TblStaffRankHistory;
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
        $keyword = \Request::get('keyword')??'';
        
            $maindata = DB::table('tbl_staff_ranks')->                
                join('tbl_staffs','tbl_staffs.id','tbl_staff_ranks.staff_id')->
                join('tbl_ranks','tbl_ranks.id','tbl_staff_ranks.rank_id')->
                select('tbl_staff_ranks.*')->
                where('tbl_staff_ranks.is_deleted', 0)->
                where(function($query) use ($keyword){
                    $query->where('tbl_staffs.full_name','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_ranks.unique_id','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_ranks.promo_date','LIKE BINARY',"%".$keyword."%")->
                            orWhere('tbl_staffs.alias','LIKE',"%".$keyword."%")->
                            orWhere('tbl_ranks.name','LIKE',"%".$keyword."%");
              })->latest()->get();

            Log::info($maindata);
            $historydata = DB::table('tbl_staff_rank_histories')->                
                join('tbl_staffs','tbl_staffs.id','tbl_staff_rank_histories.staff_id')->
                join('tbl_ranks','tbl_ranks.id','tbl_staff_rank_histories.rank_id')->
                select('tbl_staff_rank_histories.*')->
                where('tbl_staff_rank_histories.is_deleted', 0)->
                where(function($query) use ($keyword){
                    $query->where('tbl_staffs.full_name','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_rank_histories.unique_id','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_rank_histories.promo_date','LIKE BINARY',"%".$keyword."%")->
                            orWhere('tbl_staffs.alias','LIKE',"%".$keyword."%")->
                            orWhere('tbl_ranks.name','LIKE',"%".$keyword."%");
              })->latest()->get();
              Log::info($historydata);
              return $maindata->merge($historydata);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $rows = TblStaffRank::all();
        // for ($i=0; $i<sizeof($rows); $i++){
        //     $row = $rows[$i];
        //     $row->unique_id = $row->id;
        //     $row->save();
        // }
        $staff_id = $request->staff_id;
        $rank_id = $request->rank_id;
        //Log::info($request);
        $this->validate($request, [
            'rank_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query) use ($staff_id, $rank_id){
                                return $query->where('staff_id', $staff_id)->where('rank_id',$rank_id);
                            })],
            'staff_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query) use ($staff_id, $rank_id){
                                return $query->where('staff_id', $staff_id)->where('rank_id',$rank_id);
                            })],
            'part_id' => 'required',
        ]);

        $main = TblStaffRank::latest()->first();
        $history = TblStaffRankHistory::latest()->first();
        $unique = max($main->unique_id??0, $history->unique_id??0) + 1;

        $old_row = TblStaffRank::where('staff_id',$staff_id)->first();
        if ($old_row){
            TblStaffRankHistory::create([
                'rank_id' => $old_row->rank_id,
                'staff_id' => $old_row->staff_id,
                'part_id' => $old_row->part_id,
                'promo_date' => $old_row->promo_date,
                'unique_id' => $old_row->unique_id
            ]);

            $values = $request;
            $values['unique_id'] = $unique;
            $request['promo_date'] = Carbon::parse($request->promo_date);   
            $old_row->update($values->all());
            return $old_row->unique_id;
        } else {            
            return TblStaffRank::create([
                'rank_id' => $request->rank_id,
                'staff_id' => $request->staff_id,
                'part_id' => $request->part_id,
                'promo_date' => Carbon::parse($request->promo_date)->format('Y-m-d'),
                'unique_id' => $unique
            ]);
        }
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
    public function update(Request $request, $unique_id)
    {
        //Log::info($request);
        $this->validate($request, [
            'rank_id' => 'required',
            // 'staff_id' => ['required',
            //                 Rule::unique('tbl_staff_ranks')->where(function ($query) use ($request) {
            //                     return $query->where('is_deleted','0')->where('id',"<>", $request->id);
            //                 })
            //             ],
            'part_id' => 'required',
        ]);
        $values = $request;        
        $request['promo_date'] = Carbon::parse($request->promo_date);        
        $staffrank = TblStaffRank::where('unique_id', $unique_id)->first()??TblStaffRankHistory::where('unique_id',$unique_id)->first();
        $staffrank->update($values->all());
        return $unique_id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($unique_id)
    {
        $staffrank = TblStaffRank::where('unique_id', $unique_id)->first()??TblStaffRankHistory::where('unique_id',$unique_id);
        
        $staffrank->is_deleted = 1;
        $staffrank->save();
        return ['message' => 'staffrank deleted'];
    }
}

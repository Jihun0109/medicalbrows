<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblRankSchedule;
use Log;
use DB;

class RankScheduleController extends Controller
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
            return DB::table('tbl_rank_schedules')->
                            join('tbl_ranks','tbl_ranks.id','tbl_rank_schedules.rank_id')->
                            join('tbl_operable_parts','tbl_operable_parts.id','tbl_rank_schedules.part_id')->
                            select('tbl_rank_schedules.*')->
                            where('tbl_rank_schedules.is_deleted',0)->
                            where(function($query) use ($keyword){
                                $query->where('tbl_ranks.name','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_rank_schedules.id','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_operable_parts.name','LIKE',"%".$keyword."%");
                          })->latest()->get();
        }
        return TblRankSchedule::where('is_deleted', 0)
                    ->latest()
                    ->get();

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::error($request);
        $this->validate($request, [
            'rank_id' => 'required|numeric',
            'part_id' => 'required|numeric',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',            
        ]);
        return TblRankSchedule::create([
            'rank_id' => $request->rank_id, 
            'part_id' => $request->part_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_deleted' => 0,
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
        $this->validate($request, [
            'rank_id' => 'required|numeric',
            'part_id' => 'required|numeric',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',             
        ]);     
        $item = TblRankSchedule::findOrFail($id);
        $item->update($request->all());
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
        $item = TblRankSchedule::findOrFail($id);
        
        $item->is_deleted = 1;
        $item->save();
        return ['message' => 'rankschedule deleted'];
    }    
}

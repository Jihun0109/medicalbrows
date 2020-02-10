<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblRankSchedule;
use Log;

class RankScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            'rank_id' => 'numeric',
            'part_id' => 'numeric',
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
            'rank_id' => 'numeric',
            'part_id' => 'numeric',
            'start_time' => 'required|time',
            'end_time' => 'required|time|after:start_time',            
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
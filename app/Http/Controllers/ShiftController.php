<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\TblShiftHistory;
use Log;

class ShiftController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {

    }

    public function listStaffList(Request $request, $clinic_id, $staff_type)
    {
        $res = [];

        $ranks = DB::table('tbl_ranks')->select('short_name')->get();
        
        $filter = [];
        foreach($ranks as $rank){
            $obj = get_object_vars($rank);
            
            if ($staff_type == 1 && $obj['short_name'] != 'カウゼ')
                array_push($filter, $obj['short_name']);
            else if ($staff_type == 0 && $obj['short_name'] == 'カウゼ')
                array_push($filter, $obj['short_name']);
        }
        
        $staff_rank_names = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id')  
                ->join('tbl_ranks', 'tbl_ranks.id','tbl_staff_ranks.rank_id')    
                ->select('tbl_staffs.id as id','tbl_staffs.full_name','tbl_staff_ranks.rank_id', 'tbl_ranks.short_name','tbl_ranks.name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id]])
                ->whereIn('tbl_ranks.short_name',$filter)
                ->get();
        //return $staff_rank_names;
                
        return response()->json($staff_rank_names);
    }

    public function updateShift(Request $request)
    {
        //$old_tick = microtime(true);
        $staff_ids = $request->staffs;
        $dates = $request->dates;
        $month = $request->month;

        //return Carbon::parse($request->dates[0])->tz('UTC');
        foreach($staff_ids as $staff_id){
            //$o = microtime(true);
            $target_day = Carbon::createFromDate($month['year'], $month['month'], 15)->tz(config('app.timezone'));
            $dd = TblShiftHistory::where('staff_id',$staff_id)->
                whereBetween('date',[Carbon::parse($target_day)->startOfMonth(),Carbon::parse($target_day)->endOfMonth()])->
                delete();
            
            $data = [];
            foreach($dates as $date){
                array_push($data, array('staff_id'=>$staff_id, 'date'=>Carbon::parse($date)->tz(config('app.timezone'))));
            }
            TblShiftHistory::insert($data);
            //Log::error(microtime(true)-$o);
        }
        $elasped = microtime(true) - $old_tick;
        //Log::info("save time elasped = ".$elasped);
        return array("result"=>"success", "message"=> "Saved successfully.");
    }

    public function getShift(Request $request)
    {
        $staff_id = $request->staff_id;
        $year = $request->year;
        $month = $request->month;

        $target_day = Carbon::createFromDate($year, $month, 15);

        $shift = TblShiftHistory::where('staff_id', $staff_id)->
                                whereBetween('date',[Carbon::parse($target_day)->startOfMonth(),Carbon::parse($target_day)->endOfMonth()])->
                                select('date')->pluck('date')->toArray();

        return $shift;
        // return array_map(function($val) {
        //     return new Carbon($val, 'UTC');
        // },$shift);
    }
}

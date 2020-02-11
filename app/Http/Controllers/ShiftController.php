<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\TblShiftHistory;

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
        $staff_ids = $request->staffs;
        $dates = $request->dates;
        //return Carbon::parse($request->dates[0])->tz('UTC');
        foreach($staff_ids as $staff_id){
            foreach($dates as $date){
                TblShiftHistory::create([
                        'staff_id' => $staff_id,
                        'date' => Carbon::parse($date)
                ]);
            }
        }
    }
}

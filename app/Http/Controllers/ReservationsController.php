<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaff;
use App\TblOrder;
use DB;
use Log;

class ReservationsController extends Controller
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
        return view('reservations.index');
    }

    public function staffs_ranks(Request $request)
    {        
        Log::error($request->clinic_id);
        $clinic_id = $request->clinic_id;
        $res = [];

        //query
        $staff_rank_names = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.id','tbl_staffs.staff_type_id')  
                ->join('tbl_ranks', 'tbl_ranks.id','tbl_staff_ranks.rank_id')    
                ->select('tbl_staffs.full_name','tbl_staff_ranks.rank_id', 'tbl_ranks.short_name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id]])  
                ->get();
        for ( $i = 0; $i < sizeof($staff_rank_names); $i++ ){
            $temp = [];
            $rank_schedule = DB::table('tbl_rank_schedules')
                    ->where([['tbl_rank_schedules.is_deleted', 0], ['tbl_rank_schedules.rank_id', $staff_rank_names[$i]->rank_id]])  
                    ->select(DB::raw('HOUR(tbl_rank_schedules.start_time) as start_hour, HOUR(tbl_rank_schedules.end_time) as end_hour'))       
                    ->get();
            $temp = array('staff_name' => $staff_rank_names[$i]->full_name, 'rank_name'=> $staff_rank_names[$i]->short_name,
                                'time_schedule'=> $rank_schedule);
            array_push($res, $temp);
        }
        //$res = json_encode($res);
        //return sizeof($res[0]['time_schedule']);

        //create array for layout
        $idx_arr = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
        $staff = [];
        $layout = [];   
        //first grid cell
        array_push($staff, (object)[
            'x' => 0,
            'y' => 0,
            'w' => 2,
            'h' => 2,
            'i' => "",
            'static' =>  true
        ]);
        //time
        for ( $i = 0; $i < sizeof($idx_arr); $i++ ){
            $content = $idx_arr[$i].':00';
            array_push($layout, (object)[
                'x' => 0,
                'y' => $i * 3,
                'w' => 2,
                'h' => 3,
                'i' => $content,
                'static' =>  false
            ]);
        }

        //content
        for ( $i = 0; $i < sizeof($res); $i++ ) {
            //rank_layout
            array_push($staff, (object)[
                'x' => ($i + 1)* 2,
                'y' => 1,
                'w' => 2,
                'h' => 1,
                'i' => $res[$i]['rank_name'],
                'static' =>  true
            ]);
        }

        for ( $i = 0; $i < sizeof($res); $i++ ) {
            //staff_layout
            array_push($staff, (object)[
                'x' => ($i + 1)* 2,
                'y' => 0,
                'w' => 2,
                'h' => 1,
                'i' => $res[$i]['staff_name'],
                'static' =>  true
            ]);
    
            $j = 0;
            while($j < sizeof($idx_arr)){   
                $target_state = false;
                $content = '';
                $real_h = 1;
                $xh = 3;
                $add = 0;
                for ( $k = 0; $k < sizeof($res[$i]['time_schedule']); $k++ ){
                    //Log::error($res[$i]['time_schedule'][$k]->start_hour);
                    //Log::error($idx_arr[$j]);
                    if ( $idx_arr[$j] >= $res[$i]['time_schedule'][$k]->start_hour 
                            && $idx_arr[$j] < $res[$i]['time_schedule'][$k]->end_hour){
                        $target_state = true;
                        $content = '再';
                        $real_h = ($res[$i]['time_schedule'][$k]->end_hour - $res[$i]['time_schedule'][$k]->start_hour);
                        if($real_h == 1){
                            array_push($layout, (object)[
                                'x' => ($i + 1) * 2,
                                'y' => $j *3,
                                'w' => 2,
                                'h' => $real_h,
                                'i' => '',
                                'static' =>  false
                            ]);
                            $xh = 2;
                            $add = 1;
                        }                                 
                        break;
                    }                
                }    
                array_push($layout, (object)[
                    'x' => ($i + 1) * 2,
                    'y' => $j *3 + $add,
                    'w' => 2,
                    'h' => ($real_h * $xh),
                    'i' => $content,
                    'static' =>  $target_state
                ]);
                $j = $j + $real_h;         
                  
            }
        }
        $ret = array('staff_layout' => $staff, 'content_layout'=> $layout);
        return $ret;
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Log;

class ClientController extends Controller
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
       
    }

    public function rank_list(Request $request)
    {               
        $rank_list = DB::table('tbl_ranks')
            ->where('tbl_ranks.is_deleted', 0)
            ->select('tbl_ranks.id as rank_id','tbl_ranks.name')      
            ->get();
        return $rank_list;
    }
    public function staff_list(Request $request)
    {               
        $payLoad = json_decode(request()->getContent(), true);  
        $rank_id = $request['rank_info']['rank_id'];
        $rank_name =  $request['rank_info']['name'];

        $staff_list = DB::table('tbl_staff_ranks')
            ->join('tbl_staffs', 'tbl_staffs.id', 'tbl_staff_ranks.staff_id')
            ->join('tbl_operable_parts', 'tbl_operable_parts.id', 'tbl_staff_ranks.part_id')
            ->where([['tbl_staff_ranks.is_deleted', 0],['tbl_staff_ranks.rank_id', $rank_id]])
            ->select('tbl_staffs.id','tbl_staffs.alias as name','tbl_operable_parts.name as area')      
            ->get();
        return $staff_list;
    }
    public function clinic_list(Request $request)
    {                       
        $payLoad = json_decode(request()->getContent(), true);  
        $staff_id = $request['staff_info']['id'];
        if($staff_id){
            $clinic_info = DB::table('tbl_staffs')
                        ->join('tbl_clinics','tbl_clinics.id','tbl_staffs.clinic_id')
                        ->where([['tbl_staffs.is_deleted', 0],['tbl_staffs.id', $staff_id]])
                        ->select('tbl_clinics.id','tbl_clinics.name')      
                        ->get();
            //Log::info($clinic_info);            
            return $clinic_info;
        }
        $clinic_list = DB::table('tbl_clinics')
            ->where('tbl_clinics.is_deleted', 0)
            ->select('id','name')      
            ->get();
        return $clinic_list;
    }
    public function menu_list(Request $request)
    {               
        $payLoad = json_decode(request()->getContent(), true);  
        $staff_id = $request['staff_info']['id'];
        $staff_name =  $request['staff_info']['name'];

        $menu_list = DB::table('tbl_staffs')
            ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')
            ->join('tbl_menus', 'tbl_menus.rank_id', 'tbl_staff_ranks.rank_id')
            ->where([['tbl_staffs.is_deleted', 0],['tbl_staffs.id', $staff_id]])
            ->select('tbl_menus.id','tbl_menus.name')      
            ->get();
        return $menu_list;
    }
    public function canledar_info(Request $request)
    {               
        $payLoad = json_decode(request()->getContent(), true);  
        $staff_id = $request['staff_info']['id'];
        $period_day = 7;
        $next_count = $request['count'];
        $selecteddate = $request['selecteddate'];

        if($selecteddate){
            //날자우선인경우
            //$today = date("2020-11-27");
            $period_day = 1;
        }
        else{
            //$today = date("Y-m-d");
            $today = date("2020-1-27");
            $timestamp = strtotime($today.' +'.($next_count * $period_day).'days');
            $selecteddate = date("Y-m-d", $timestamp);
            //Log::Info($selecteddate);
           
        }
        $calendarLayout = $this->getLayoutFromDate($staff_id, $selecteddate, $period_day);
            
            // {"x":12,"y":4,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
            // {"x":12,"y":5,"w":2,"h":1,"i":"◯", "static": true, "selectable":true, "data":{"date":"2020-02-05", "week":"水", "time":["13:20~16:00"],"clinic":"表参道院"}},
            // {"x":12,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
            
            // {"x":14,"y":4,"w":2,"h":1,"i":"◯", "static": true, "selectable":true, "data":{"date":"2020-02-05", "week":"木", "time":["09:20~12:00","11:20~14:00"],"clinic":"表参道院"}},
            // {"x":14,"y":5,"w":2,"h":1,"i":"✕", "static": true, "selectable":false},
            // {"x":14,"y":6,"w":2,"h":1,"i":"✕", "static": true, "selectable":false}
            // ]';
        return $calendarLayout;
    }
    public function getLayoutFromDate($staff_id, $param_date, $period_day)
    {
        $calendarLayout = [];
        $cell_width = 2;
        $cell_height = 1;

        $layout_width = $cell_width * ($period_day + 1);
        
        //cur date info
        $year =  date('Y', strtotime($param_date));
        $month =  date('n', strtotime($param_date));
        $day =  date('j', strtotime($param_date));
        $week = date('w', strtotime($param_date));
        $daysOfWeek = ['日','月', '火', '水', '木', '金', '土'];

        //next date info
        $next_month = $month + 1;
        $next_year = $year + 1;
        if ($month == 12) {
            $next_month = 1;
        }
        //last day of the month
        $max_day = date('t', mktime(0, 0, 0, $month, 1, $year));
        //Log::info('NextYear:'.$next_year.'--NextMonth:'.$next_month.'--MaxDayAmonth:'.$max_day);

        //first cell
        array_push($calendarLayout, (object)[
            'x' => 0,
            'y' => 0,
            'w' => $cell_width,
            'h' => 4 * $cell_height,
            'i' => "",
            'static' =>  false,
            'selectable' => false,
        ]);
        //year cell

        $delta_width = $max_day - $day + 1;
        if($month == 12 && $delta_width < $period_day)
        {            
            array_push($calendarLayout, (object)[
                'x' => $cell_width,
                'y' => 0,
                'w' => $cell_width * $delta_width,
                'h' => $cell_height,
                'i' => $year,
                'static' =>  false,
                'selectable' => false,
            ]);
            array_push($calendarLayout, (object)[
                'x' => $cell_width + ($cell_width * $delta_width),
                'y' => 0,
                'w' => $cell_width * ($period_day - $delta_width),
                'h' => $cell_height,
                'i' => $next_year,
                'static' =>  false,
                'selectable' => false,
            ]);
        }else{
            array_push($calendarLayout, (object)[
                'x' => $cell_width,
                'y' => 0,
                'w' => $cell_width * $period_day,
                'h' => $cell_height,
                'i' => $year,
                'static' =>  false,
                'selectable' => false,
            ]);
            array_push($calendarLayout, (object)[
                'x' => $cell_width + ($cell_width * $period_day),
                'y' => 0,
                'w' => 0,
                'h' => 0,
                'i' => '',
                'static' =>  false,
                'selectable' => false,
            ]);
        }
        //month cell
        if($delta_width < $period_day){
            array_push($calendarLayout, (object)[
                'x' => $cell_width,
                'y' => $cell_height,
                'w' => $cell_width * $delta_width,
                'h' => $cell_height,
                'i' => $month.'月',
                'static' =>  false,
                'selectable' => false,
            ]);
            array_push($calendarLayout, (object)[
                'x' => $cell_width + ($cell_width * $delta_width),
                'y' => $cell_height,
                'w' => $cell_width * ($period_day - $delta_width),
                'h' => $cell_height,
                'i' => $next_month.'月',
                'static' =>  false,
                'selectable' => false,
            ]);
        }else{
            array_push($calendarLayout, (object)[
                'x' => $cell_width,
                'y' => $cell_height,
                'w' => $cell_width * $period_day,
                'h' => $cell_height,
                'i' => $month.'月',
                'static' =>  false,
                'selectable' => false,
            ]);
            array_push($calendarLayout, (object)[
                'x' => $cell_width + ($cell_width * $period_day),
                'y' => $cell_height,
                'w' => 0,
                'h' => 0,
                'i' => '',
                'static' =>  false,
                'selectable' => false,
            ]);
        }
        //days cell
        $cur_day = $day;
        $cur_month = $month;
        $cur_year = $year;
        $cur_week_num = $week;    
        $date_array = [];
        for ($j = 0; $j < $period_day; $j++) {
            $cur_date = $cur_year.'-'.$cur_month.'-'.$cur_day;
            $color = 'black';
            $str_week = $daysOfWeek[$cur_week_num % 7];
            if($cur_week_num % 7 == 0) //sunday--->red
                $color = 'red';
            if($cur_week_num % 7 == 6) //saturday-->blue
                $color = 'blue';
            array_push($calendarLayout, (object)[
                'x' => $cell_width * ($j + 1),
                'y' => 2 * $cell_height,
                'w' => $cell_width,
                'h' => 2 * $cell_height,
                'i' => '<div style="color:'.$color.';">'.$cur_day.'<br>('.$str_week.')</div>',
                'static' =>  false,
                'selectable' => false,
            ]);            
            $cur_day++;
            if($cur_day > $max_day)
            {
                $cur_day = 1;
                $cur_month++;
                if($cur_month > 12){
                    $cur_month = 1;
                    $cur_year++;
                }                    
            }               
            $cur_week_num++;
            Log::Info(date("Y-m-d",strtotime($cur_date)));
            array_push($date_array, (object)['date' => date("Y-m-d",strtotime($cur_date)),'week' => $str_week]);
        }
        Log::Info($date_array);
        
        //******************************************************************* */
        //랭크스케줄로부터 시간령역을 구한다
        $staff_id = 1;
        $time_schedule = DB::table('tbl_staffs')
                        ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')
                        ->join('tbl_rank_schedules', 'tbl_rank_schedules.rank_id', 'tbl_staff_ranks.rank_id')
                        ->where([['tbl_staffs.is_deleted', 0],['tbl_staffs.id', $staff_id]])
                        ->select('tbl_rank_schedules.start_time','tbl_rank_schedules.end_time')      
                        ->get();
        Log::Info($time_schedule);
        //cell info
        $first_row = ['午前','日中','夕方'];
        for ($x = 0; $x <= $period_day; $x++)
        {
            //현재 날자가 shift_history에 있는가를 조사하고 있으면 skip
            for ($y = 0; $y < 3; $y++)
            {
                $i_value = "✕";
                $static = true;
                if($x == 0){ //first row --> '午前','日中','夕方'
                    $i_value = $first_row[$y];
                    $static = false;
                }
                array_push($calendarLayout, (object)[
                    'x' => $cell_width * $x,
                    'y' => (4 + $y) * $cell_height,
                    'w' => $cell_width,
                    'h' => $cell_height,
                    'i' => $i_value,
                    'static' =>  $static,
                    'selectable' => false,
                ]);  
            }
        }
        $ret = array('layout_width'=>$layout_width , 'layout'=> $calendarLayout);
        return $ret;
    }




}

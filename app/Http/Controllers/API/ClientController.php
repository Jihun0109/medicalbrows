<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblCustomer;
use App\TblOrder;
use App\TblOrderHistory;
use DB;
use Log;

use App\Mail\OrderReserved;
use App\Mail\WelcomeMail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Storage;

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
            ->select('tbl_ranks.id as id','tbl_ranks.name')      
            ->get();
        return $rank_list;
    }
    public function staff_list(Request $request)
    {               
        $payLoad = json_decode(request()->getContent(), true);  
        $rank_id = $request['rank_info']['id'];
        $rank_name =  $request['rank_info']['name'];
        $clinic_id =  $request['clinic_info']['id'];
        $staff_list = [];
        if($rank_id){
            $staff_list = DB::table('tbl_staff_ranks')
            ->join('tbl_staffs', 'tbl_staffs.id', 'tbl_staff_ranks.staff_id')
            ->join('tbl_operable_parts', 'tbl_operable_parts.id', 'tbl_staff_ranks.part_id')
            ->where([['tbl_staff_ranks.is_deleted', 0],['tbl_staff_ranks.rank_id', $rank_id]])
            ->select('tbl_staffs.id','tbl_staffs.alias as name','tbl_operable_parts.name as area')      
            ->get();
        }
        else if($clinic_id){
            $staff_list = DB::table('tbl_staffs')            
            ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')
            ->join('tbl_ranks', 'tbl_ranks.id', 'tbl_staff_ranks.rank_id')
            ->join('tbl_operable_parts', 'tbl_operable_parts.id', 'tbl_staff_ranks.part_id')
            ->where([['tbl_staff_ranks.is_deleted', 0],['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id]])
            ->select('tbl_staffs.id','tbl_staffs.alias as name','tbl_operable_parts.name as area','tbl_ranks.id as rank_id' ,'tbl_ranks.name as rank_name')      
            ->get();
        }
        return $staff_list;
    }

    public function staff_list_withdate(Request $request)
    {               
        $payLoad = json_decode(request()->getContent(), true);  
        $selected_date = $request['date'];
        //Log::info($payLoad);
        $staff_list = DB::table('tbl_staffs')            
            ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')
            ->join('tbl_ranks', 'tbl_ranks.id', 'tbl_staff_ranks.rank_id')
            ->join('tbl_operable_parts', 'tbl_operable_parts.id', 'tbl_staff_ranks.part_id')
            ->where([['tbl_staff_ranks.is_deleted', 0],['tbl_staffs.is_deleted', 0]])
            ->select('tbl_staffs.id','tbl_staffs.alias as name','tbl_operable_parts.name as area','tbl_ranks.id as rank_id' ,'tbl_ranks.name as rank_name')      
            ->get();
        
        $ret_staff = [];
        for ( $i = 0; $i < sizeof($staff_list); $i++ ){
            //shift table
            if (sizeof(DB::table("tbl_shift_histories")->where(['staff_id'=>$staff_list[$i]->id, 'date'=>$selected_date])->get()) > 0){
                continue;
            }  
            //이 시술자에게 예약할수 있는가 판단(order_history->rank_schedule_id 검사)
            $rank_schedule_id = DB::table('tbl_rank_schedules')
                                ->join('tbl_ranks','tbl_ranks.id','tbl_rank_schedules.rank_id')
                                ->join('tbl_staff_ranks','tbl_staff_ranks.rank_id','tbl_ranks.id')
                                ->where(['tbl_rank_schedules.is_deleted'=>0,'tbl_staff_ranks.staff_id'=>$staff_list[$i]->id])
                                ->select('tbl_rank_schedules.id','tbl_rank_schedules.start_time','tbl_rank_schedules.end_time')
                                ->get();
            $b_possible_staff = false;
            for ( $j = 0; $j < sizeof($rank_schedule_id); $j++ ){
                //스케쥴들중에 그날 예약표에 존재하지 않는것이 하나라도 있으면...쓸만한 시술자!
                if (sizeof(DB::table("tbl_order_histories")
                            ->where(['rank_schedule_id'=>$rank_schedule_id[$j]->id, 'order_date'=>$selected_date])->get()) == 0)
                {
                    $staff_info = DB::table('tbl_staffs')
                                ->where(['id'=>$staff_list[$i]->id,'is_deleted'=>0])
                                ->first();
                    //이 스케쥴에 맞는 상담원이 없으면 쓸모없음.(클리닉에 있는 상담원목록은 우에서 미리 얻자..물론 shift 참조~)
                    $counselor_list = $this->counselor_list($staff_info->clinic_id, $selected_date, $rank_schedule_id[$j]->id, 0);
                    if(sizeof($counselor_list) > 0){
                        $b_possible_staff = true;
                        break;
                    }
                }  
            }
            if(!$b_possible_staff)
                continue;
            array_push($ret_staff, $staff_list[$i]);
        }
        //Log::info($ret_staff);
        return $ret_staff;
    }

    public function clinic_list(Request $request)
    {                       
        $payLoad = json_decode(request()->getContent(), true);  
        $staff_id = $request['staff_info']['id'];
        //Log::Info($payLoad);
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
        $selected_date = $request->date;//date("Y-m-d");
        //Log::info($payLoad);
        $rank_info = DB::table('tbl_staffs')
        ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')     
        ->where(['tbl_staffs.is_deleted'=>0, 'tbl_staffs.id'=>$staff_id])
        ->select('tbl_staff_ranks.rank_id','tbl_staff_ranks.staff_id')      
        ->first();
                
        $menu_list = DB::table('tbl_menus')->        
        where(function($query) use ($selected_date) {
            $query->where(function($query) use ($selected_date){
                $query->where('start_time', '<=', $selected_date)->where('end_time','>=', $selected_date);
            })->
            orWhere(function($query) use ($selected_date) {
                $query->where('start_time', '<=', $selected_date)->whereNull('end_time');
            });
        })->
        where(['tbl_menus.is_deleted'=>0, 'tbl_menus.rank_id'=>$rank_info->rank_id])->
        select('tbl_menus.id','tbl_menus.name')->
        get();

        // $menu_list = DB::table('tbl_staffs')
        //     ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')
        //     ->join('tbl_menus', 'tbl_menus.rank_id', 'tbl_staff_ranks.rank_id')
        //     ->where([['tbl_staffs.is_deleted', 0],['tbl_staffs.id', $staff_id]])
        //     ->select('tbl_menus.id','tbl_menus.name')      
        //     ->get();

        //Log::info($menu_list);
        return $menu_list;
    }

    public function canledar_info(Request $request)
    {               
        $payLoad = json_decode(request()->getContent(), true);  
        $order_type = $request['order_type'];
        $staff_id = $request['staff_info']['id'];
        $period_day = $request['weekmethod'];
        $next_count = $request['count'];
        $selecteddate = $request['selecteddate'];
        $menu_id = $request['menu_info']['id'];
        $rank_id = $request['rank_info']['id'];
        //Log::Info($payLoad);
        if($selecteddate){
            //날자우선인경우
            //$today = date("2020-11-27");
            $period_day = 1;
        }
        else{
            $today = date("Y-m-d");
            $timestamp = strtotime($today.' +'.($next_count * $period_day).'days');
            $selecteddate = date("Y-m-d", $timestamp);    
        }
        $calendarLayout = $this->getLayoutFromDate($order_type, $staff_id, $selecteddate, $period_day, $menu_id, $rank_id);
        return $calendarLayout;
    }
    public function getLayoutFromDate($order_type, $staff_id, $param_date, $period_day, $menu_id, $rank_id)
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
            //Log::Info(date("Y-m-d",strtotime($cur_date)));
            array_push($date_array, (object)['date' => date("Y-m-d",strtotime($cur_date)),'week' => $str_week]);
        }
        //Log::Info($date_array);
        
        //******************************************************************* */
        //cell info
        $first_row = ['午前','日中','夕方'];
        for ($x = 0; $x <= $period_day; $x++)
        {
            //오전 오후 저녁 마당
            if($x == 0){
                for ($y = 0; $y < 3; $y++)
                {
                    array_push($calendarLayout, (object)[
                        'x' => $cell_width * $x,
                        'y' => (4 + $y) * $cell_height,
                        'w' => $cell_width,
                        'h' => $cell_height,
                        'i' => $first_row[$y],
                        'static' =>  false,
                        'selectable' => false,
                    ]);  
                }
                continue;
            }
            //상담원 정보를 얻기전에 먼저 스타프(대신 rank_id리용)가 선택된 날자에 선택된 메뉴로 서비스 할수 있는지 menu_table에서 날자 검사진행.
            $is_menu = true;
            $order_info = [];
            $date_x = $date_array[$x - 1]->date;
            $menu_list = DB::table('tbl_menus')->        
            where(function($query) use ($date_x) {
                $query->where(function($query) use ($date_x){
                    $query->where('start_time', '<=', $date_x)->where('end_time','>=', $date_x);
                })->
                orWhere(function($query) use ($date_x) {
                    $query->where('start_time', '<=', $date_x)->whereNull('end_time');
                });
            })->
            where(['tbl_menus.is_deleted'=>0, 'tbl_menus.id'=>$menu_id, 'tbl_menus.rank_id'=>$rank_id])->
            select('tbl_menus.id','tbl_menus.name')->
            get();
            //Log::info($menu_list);
            if(count($menu_list) == 0)
            {
                $is_menu = false;                
            }
            else{
                $order_info = $this->getCounselor($order_type, $staff_id, $date_x);
            }
            
            //for ($y = 0; $y < count($order_info); $y++)
            for ($y = 0; $y < count($first_row); $y++) //오전, 오후, 저녁 ->3
            {
                //Log::Info($order_info[$y]);
                $i_value = "✕";
                $selectable = false;
                $data = '';
                if($is_menu){
                    $data = $order_info[$y];
                    if(!empty($data))
                    {
                        $i_value = "◯";
                        $selectable = true;
                    }
                }
                array_push($calendarLayout, (object)[
                    'x' => $cell_width * $x,
                    'y' => (4 + $y) * $cell_height,
                    'w' => $cell_width,
                    'h' => $cell_height,
                    'i' => $i_value,
                    'static' =>  true,
                    'selectable' => $selectable,
                    'order_info' => $data,
                    'date_info' => $date_array[$x - 1],
                ]); 
            }
        }
        $ret = array('layout_width'=>$layout_width , 'layout'=> $calendarLayout);
        return $ret;
    }
    function getCounselor($order_type, $staff_id, $date)
    {
        $order_info = '';
        $morning = [];
        $afternoon = [];
        $evening = [];
        //shift표로 staff검사,
        if (sizeof(DB::table("tbl_shift_histories")->where(['staff_id'=>$staff_id, 'date'=>$date])->get()) > 0){
            $order_info = array($morning, $afternoon, $evening);
            return $order_info;
        }   
        //랭크스케줄로부터 시간령역을 구한다
        //$staff_id = 4;
        $rank_schedule = DB::table('tbl_staffs')
                        ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id', 'tbl_staffs.id')
                        ->join('tbl_ranks', 'tbl_ranks.id', 'tbl_staff_ranks.rank_id')
                        ->join('tbl_rank_schedules', 'tbl_rank_schedules.rank_id', 'tbl_staff_ranks.rank_id')
                        ->where([['tbl_staffs.is_deleted', 0],['tbl_staffs.id', $staff_id],['tbl_rank_schedules.is_deleted', 0]])
                        ->select('tbl_staffs.clinic_id','tbl_ranks.short_name','tbl_rank_schedules.id','tbl_rank_schedules.start_time','tbl_rank_schedules.end_time',DB::raw('HOUR(tbl_rank_schedules.start_time) as start_hour'),DB::raw('HOUR(tbl_rank_schedules.end_time) as end_hour'))      
                        ->get();
        //Log::Info($rank_schedule);

        //스타프의 랭크정보로부터 가능한 모든 상담원들의 목록을 얻어낸다.      
        for($i = 0; $i < sizeof($rank_schedule); $i++)
        {
            //이때 스타프의 order_history 에 있는 랭크스케줄 제거한다.  
            if(sizeof(DB::table("tbl_order_histories")->where(['staff_id'=>$staff_id, 'order_date'=>$date, 'rank_schedule_id'=>$rank_schedule[$i]->id])->get()) > 0)
                continue;            
            //랭크스케쥴로부터 오전 오후 점심을 갈라서 보관한다            
            //이 경우는 상담원이 필요없다.
            if($rank_schedule[$i]->short_name == 'T' || $rank_schedule[$i]->short_name == 'NA' || $order_type == '再診')
            {
                if($rank_schedule[$i]->end_hour <= 14){                
                    array_push($morning, (object)[
                        'rank_schedule_id' => $rank_schedule[$i]->id,
                        'counselor_info' => '',
                        'start_time' => date('H:i', strtotime($rank_schedule[$i]->start_time)),
                        'end_time' => date('H:i', strtotime($rank_schedule[$i]->end_time)),
                        ]);
                }
                else if($rank_schedule[$i]->end_hour <= 17){
                    array_push($afternoon, (object)[
                        'rank_schedule_id' => $rank_schedule[$i]->id,
                        'counselor_info' => '',
                        'start_time' => date('H:i', strtotime($rank_schedule[$i]->start_time)),
                        'end_time' => date('H:i', strtotime($rank_schedule[$i]->end_time)),
                        ]);
                }
                else if($rank_schedule[$i]->end_hour <= 19){
                    array_push($evening, (object)[
                        'rank_schedule_id' => $rank_schedule[$i]->id,
                        'counselor_info' => '',
                        'start_time' => date('H:i', strtotime($rank_schedule[$i]->start_time)),
                        'end_time' => date('H:i', strtotime($rank_schedule[$i]->end_time)),
                        ]);
                }
            }
            else //얻어온 상담원 목록을 리용한다.
            {
                $counselor_list = $this->counselor_list($rank_schedule[$i]->clinic_id, $date, $rank_schedule[$i]->id, 0);//0은 후에 좀 따지자...
                if(sizeof($counselor_list) == 0) continue;
                if($rank_schedule[$i]->end_hour <= 14){       //목록에서 첫 상담원 선택          
                    array_push($morning, (object)[
                        'rank_schedule_id' => $rank_schedule[$i]->id,
                        'counselor_info' => $counselor_list[0],
                        'start_time' => $counselor_list[0]['interview_start'],
                        'end_time' => date('H:i', strtotime($rank_schedule[$i]->end_time)),
                        ]);
                }
                else if($rank_schedule[$i]->end_hour <= 17){
                    array_push($afternoon, (object)[
                        'rank_schedule_id' => $rank_schedule[$i]->id,
                        'counselor_info' => $counselor_list[0],
                        'start_time' => $counselor_list[0]['interview_start'],
                        'end_time' => date('H:i', strtotime($rank_schedule[$i]->end_time)),
                        ]);
                }
                else if($rank_schedule[$i]->end_hour <= 19){
                    array_push($evening, (object)[
                        'rank_schedule_id' => $rank_schedule[$i]->id,
                        'counselor_info' => $counselor_list[0],
                        'start_time' => $counselor_list[0]['interview_start'],
                        'end_time' => date('H:i', strtotime($rank_schedule[$i]->end_time)),
                        ]);
                }
            }

        }
        // Log::Info($morning);
        // Log::Info($afternoon);
        // Log::Info($evening);
        $order_info = array($morning, $afternoon, $evening);
        return $order_info;
    }

    // 스타프에 해당한 상담원 목록 리턴
    public function counselor_list($clinic_id, $selected_date, $rank_schedule_id,$order_history_id)
    {        
        $res = [];
        //get start time from rank_schedule_id
        $start_time = DB::table('tbl_rank_schedules')
            ->where([['tbl_rank_schedules.is_deleted', 0], ['tbl_rank_schedules.id', $rank_schedule_id]])
            ->select(DB::raw('HOUR(tbl_rank_schedules.start_time) as start_hour'))      
            ->get();
        //Log::Info($rank_schedule_id);
        $cinfo = $this->generalCounInfo();
        $possible_endtime_addr = $cinfo['end_hour_array'];//[ 10, 12, 15, 17 ];
        //nearest value
        $interviewer_rank_schedule_id = 13;//이미 정해진 4개 수자이다.10,11,12,13
        $min = $possible_endtime_addr[3];
        for($i = 0; $i < sizeof($possible_endtime_addr); $i++)
        {
            if($possible_endtime_addr[$i] > $start_time[0]->start_hour )
            {
                $interviewer_rank_schedule_id = 10 + ($i - 1);
                $min = $possible_endtime_addr[$i - 1];
                break;
            }
        }
        //possible all counselor_id, counselor_name, interview_start,interview_end, from start time
        //query 해당 클리닉의 상담원 목록을 리턴
        $counselor_list = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id') 
                ->select('tbl_staffs.id as interviewer_id','tbl_staffs.full_name as counselor_name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id], ['tbl_staff_ranks.rank_id', 9]])  
                ->get();
        
        //order_history에 예약된 상담원의 시간대를 구하고 제거한다.
        for ( $i = 0; $i < sizeof($counselor_list); $i++ ){
            //shift table
            if (sizeof(DB::table("tbl_shift_histories")->where(['staff_id'=>$counselor_list[$i]->interviewer_id, 'date'=>$selected_date])->get()) > 0){
                continue;
            }    
            $temp = [];
            // 상담원이 인터뷰어로 예약되여 있으면 그 시간을 리턴.
            $remove_time_with_order = DB::table('tbl_order_histories')
                            ->join('tbl_rank_schedules','tbl_rank_schedules.id','tbl_order_histories.rank_schedule_id')
                            ->where([['tbl_order_histories.staff_id',$counselor_list[$i]->interviewer_id],['tbl_order_histories.order_date', $selected_date],['tbl_order_histories.is_deleted', 0]])
                            ->select('tbl_order_histories.id', DB::raw('HOUR(tbl_rank_schedules.end_time) as end_hour'))
                            ->get();
            $bflag = false;
            for( $j = 0; $j < sizeof($remove_time_with_order); $j++ ){
                if($remove_time_with_order[$j]->end_hour === $min && $order_history_id != $remove_time_with_order[$j]->id){
                    //Log::info($remove_time_with_order[$j]->id);
                    $bflag = true;
                    break;
                }
            }
            if(!$bflag){
                $interview_start = ($min - 1).':20';
                $interview_end = $min.':00';
                $temp = array(
                        'interviewer_id' => $counselor_list[$i]->interviewer_id,
                        'counselor_name' => $counselor_list[$i]->counselor_name, 
                        'timename' => ($interview_start.'~'.$interview_end.'('.$counselor_list[$i]->counselor_name.')'), 
                        'interview_start' => $interview_start, 
                        'interview_end' => $interview_end, 
                        'interviewer_rank_schedule_id' => $interviewer_rank_schedule_id);
                array_push($res, $temp);
            }
        }
        
        //Log::info($res);
        return $res;
    }

    public function order_create(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        $order_info = $payLoad['order_info'];
        $customer_info = $payLoad['user_info'];
         //Log::Info($payLoad);
         //return;
        $order_serial_id = $request->order_serial_id;

        //create customer: zipcode, city_name, address2 non-used
        $customer = TblCustomer::create([
            'email' => $customer_info['email'],
            'gender' => $customer_info['sex'],
            'first_name' => $customer_info['first_name'],
            'last_name' => $customer_info['last_name'],
            'address' => $customer_info['address1'],
            'phonenumber' => $customer_info['phonenumber'],
            'birthday' => $customer_info['birthday'],
            'is_deleted' => 0
        ]);
        //신규인 경우 
        if ($order_info['order_type'] == "新規")
        {
            // Second create Order 
            $order_serial_id = sprintf('%s-%06d', time(), $order_info['staff_info']['id']);

            //new order --> staff:order_history and counselor: order_history(NA, T :exeption)
            if($order_info['rank_info']['name']  != 'ノービスアーティスト' && $order_info['rank_info']['name']  != 'トレイニー')
            {
                //counselor: order_history
                $order_history = TblOrderHistory::create([
                    'clinic_id' => $order_info['clinic_info']['id'],
                    'staff_id' => $order_info['time_schedule_info']['counselor_info']['interviewer_id'],
                    'rank_id' => 9,//counselor_rank_id
                    'status' => 0,//always zero when created
                    'staff_choosed' => $order_info['staff_choosed'],
                    'rank_schedule_id' => $order_info['time_schedule_info']['counselor_info']['interviewer_rank_schedule_id'],
                    'order_type' => $order_info['order_type'],
                    'order_date' => $order_info['calendar_info']['date'],
                    'order_route' => '電話',//$order_info['order_route'],
                    'note' => $customer_info['note'],
                    'order_serial_id' => $order_serial_id,
                    'customer_id' => $customer->id,
                    'menu_id' => $order_info['menu_info']['id'],
                    'is_deleted' => 0
                ]);
            }
        }   

        // //재진인경우 예약ID가 존재하지 않으면 오유통보 현시 
        // if($order_info['order_type'] == "再診" && is_null($order)){
        //     return 0;
        // }

        if ($order_info['order_type'] == "新規")
        {
            $order_history = TblOrderHistory::create([
                'clinic_id' => $order_info['clinic_info']['id'],
                'staff_id' => $order_info['staff_info']['id'],
                'rank_id' => $order_info['rank_info']['id'],
                'interviewer_id' => $order_info['time_schedule_info']['counselor_info']?$order_info['time_schedule_info']['counselor_info']['interviewer_id']:null,
                'interview_start' =>  $order_info['time_schedule_info']['counselor_info']?$order_info['time_schedule_info']['counselor_info']['interview_start']:null,
                'interview_end' =>  $order_info['time_schedule_info']['counselor_info']?$order_info['time_schedule_info']['counselor_info']['interview_end']:null,
                'status' => 0,//default
                'staff_choosed' => $order_info['staff_choosed'],
                'rank_schedule_id' => $order_info['time_schedule_info']['rank_schedule_id'],
                'order_type' => $order_info['order_type'],
                'order_date' => $order_info['calendar_info']['date'],
                'menu_id' => $order_info['menu_info']['id'],
                'order_route' => '電話',//$order_info['order_route'],
                'order_serial_id' => $order_serial_id,
                'customer_id' => $customer->id,
                'note' => $customer_info['note'],
                'is_deleted' => 0
            ]);
        }
        else{//再診 == neworder : NA, T
            $schedule_info = DB::table('tbl_rank_schedules')->where('id', $order_info['time_schedule_info']['rank_schedule_id'])->first();
            $order_serial_id = sprintf('%s-%06d', time(), $order_info['staff_info']['id']);
            $order_history = TblOrderHistory::create([
                'clinic_id' => $order_info['clinic_info']['id'],
                'staff_id' => $order_info['staff_info']['id'],
                'rank_id' => $order_info['rank_info']['id'],
                'interviewer_id' => $order_info['order_type'] == "カウンセ"?$order_info['staff_info']['id']:null,
                'interview_start' => $order_info['order_type'] == "カウンセ"?$schedule_info->start_time:null,
                'interview_end' => $order_info['order_type'] == "カウンセ"?$schedule_info->end_time:null,
                'status' => 0,//default
                'staff_choosed' => $order_info['staff_choosed'],
                'rank_schedule_id' => $order_info['time_schedule_info']['rank_schedule_id'],
                'order_type' => $order_info['order_type'],
                'order_date' => $order_info['calendar_info']['date'],
                'menu_id' => $order_info['menu_info']['id'],
                'order_route' => '電話',//$order_info['order_route'],
                'order_serial_id' => $order_serial_id,
                'customer_id' => $customer->id,
                'note' => $customer_info['note'],
                'is_deleted' => 0
            ]);
        }
        $ret = array('mail'=> $customer_info['email'], 'order_serial_id' => $order_serial_id);
        return $ret;
    }

    public function order_update(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        $order_info = $payLoad['order_info'];
        $user_info = $payLoad['user_info'];
        
        //update customer table
        $customer = TblCustomer::where('id', $order_info['customer_info']['id'])->first();

        $customer->first_name = $user_info['first_name'];
        $customer->last_name = $user_info['last_name'];
        $customer->email = $user_info['email'];
        $customer->phonenumber = $user_info['phonenumber'];
        $customer->address = $user_info['address1'];
        $customer->birthday = $user_info['birthday'];
        $customer->save();
        $ret = array('mail'=> $user_info['email'], 'order_serial_id' => $order_info['old_order_info']['order_serial_id']);
        return $ret;
    }
    public function order_cancel(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        $order_serial_id = $payLoad['order_serial_id'];
        //get order info

        TblOrderHistory::where(['order_serial_id'=>$order_serial_id, 'is_deleted'=>0])
                    ->update([
                        'status' =>  4,
                    ]);
        
        $order_history = DB::table('tbl_order_histories')
        ->where([['is_deleted', 0],['order_serial_id', $order_serial_id]])
        ->get();
        //Log::info($order_history);
        $customer = DB::table('tbl_customers')
                    ->where([['is_deleted', 0],['id', $order_history[0]->customer_id]])
                    ->select('id','email','gender','first_name','last_name','address','phonenumber','birthday')
                    ->first();
        
        return $customer->email;
    }
    public function get_orderinfo(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        $order_serial_id = $payLoad['order_serial_id'];
        $phonenumber = $payLoad['phonenumber'];
        //get order info
        $order_history = DB::table('tbl_order_histories')
                    ->where([['is_deleted', 0],['order_serial_id', $order_serial_id]])
                    ->get();
        //Log::info($order_history);
        if($order_history->isEmpty())
            return 'wrongID';
        $customer = DB::table('tbl_customers')
                    ->where([['is_deleted', 0], ['phonenumber', $phonenumber],['id', $order_history[0]->customer_id]])
                    ->select('id','email','gender','first_name','last_name','address','phonenumber','birthday')
                    ->first();

        if(is_null($customer))
            return 'wrongPhone';
        $order_info = [];
        $temp_history = [];
        $order_type = $order_history[0]->order_type;
        $daysOfWeek = ['日','月', '火', '水', '木', '金', '土'];
        $week = date('w', strtotime($order_history[0]->order_date));
        $str_week = $daysOfWeek[$week];

        $counselor_info = [];
        $cinfo = $this->generalCounInfo();
        if($order_type == '新規')
        {
            for($i = 0; $i < sizeof($order_history); $i++){

                if($order_history[$i]->rank_id == $cinfo['rank_id'])
                {
                    $counselor_info['interviewer_id'] = $order_history[$i]->staff_id;
                    $counselor_info['interviewer_rank_schedule_id'] = $order_history[$i]->rank_schedule_id;
                }
                else{
                    $counselor_info['interview_start'] = date('H:i', strtotime($order_history[$i]->interview_start));
                    $counselor_info['interview_end'] = date('H:i', strtotime($order_history[$i]->interview_end));
                    $temp_history = $order_history[$i];
                }
            }
        }
        else{
            $temp_history = $order_history[0];
        }

        $order_info['order_type'] = $order_type;
        $order_info['customer_id'] = $customer->id;
        $order_info['order_serial_id'] = $temp_history->order_serial_id;
        $order_info['order_date'] = $temp_history->order_date;
        $order_info['week'] = $str_week;
        $order_info['staff_choosed'] = $temp_history->staff_choosed;
        $order_info['order_route'] = $temp_history->order_route;

        $menu_info = DB::table('tbl_menus')->where(['is_deleted'=>0,'id'=>$temp_history->menu_id])
                        ->select('id','name')->first();
        $staff_info = DB::table('tbl_staffs')->where(['is_deleted'=>0,'id'=>$temp_history->staff_id])
                        ->select('id','alias')->first();
        $clinic_info = DB::table('tbl_clinics')->where(['is_deleted'=>0,'id'=>$temp_history->clinic_id])
                        ->select('id','name')->first();      
        $rank_info = DB::table('tbl_ranks')->where(['is_deleted'=>0,'id'=>$temp_history->rank_id])
                        ->select('id','name')->first();      
        $rs_info =  DB::table('tbl_rank_schedules')->where(['is_deleted'=>0,'id'=>$temp_history->rank_schedule_id])
                        ->select('id',DB::raw('DATE_FORMAT(start_time,"%H:%i") as s_time'),DB::raw('DATE_FORMAT(end_time,"%H:%i") as e_time'))->first();  
        $part_info = DB::table('tbl_operable_parts')                        
                        ->join('tbl_staff_ranks','tbl_staff_ranks.part_id','tbl_operable_parts.id')
                        ->where(['tbl_operable_parts.is_deleted'=>0,'tbl_staff_ranks.staff_id'=>$staff_info->id])
                        ->select('tbl_operable_parts.id','tbl_operable_parts.name')   
                        ->first();   

        $order_info['menu_info'] = $menu_info;        
        $order_info['staff_info'] = $staff_info;        
        $order_info['clinic_info'] = $clinic_info;        
        $order_info['rank_info'] = $rank_info;        
        $order_info['rs_info'] = $rs_info; 
        $order_info['part_info'] = $part_info; 
        $order_info['counselor_info'] = $counselor_info;   
        $order_info['time_schedule'] = $order_type == '新規'?
                    $counselor_info['interview_start'].'~'.$rs_info->e_time:$rs_info->s_time.'~'.$rs_info->e_time;   

        $ret = array('order_info'=>$order_info, 'customer_info'=>$customer);
     
        //Log::info($ret);
        return $ret;
    }
    public function generalCounInfo()
    {
        $rank_info = DB::table('tbl_ranks')
                ->where([['tbl_ranks.is_deleted', 0],['tbl_ranks.short_name','LIKE',"%カウ%"]])
                ->select('tbl_ranks.id','tbl_ranks.name','tbl_ranks.short_name')
                ->first();
        
        $rank_schedule_info = DB::table('tbl_rank_schedules')
                ->where(['is_deleted'=>0, 'rank_id'=>$rank_info->id])
                ->select('tbl_rank_schedules.id','tbl_rank_schedules.start_time','tbl_rank_schedules.end_time',
                    DB::raw('HOUR(tbl_rank_schedules.start_time) as start_hour'),DB::raw('HOUR(tbl_rank_schedules.end_time) as end_hour'))    
                ->get();
        $end_hour_array = [];
        for($i = 0; $i < sizeof($rank_schedule_info); $i++)
        {
            array_push($end_hour_array, $rank_schedule_info[$i]->end_hour);
        }
        $ret = array('rank_id'=>$rank_info->id,'rank_name'=>$rank_info->name,'rank_sname'=>$rank_info->short_name,
                    'end_hour_array'=>$end_hour_array);

        return $ret;
    }

    public function sendmail(Request $request)
    {
        //Log::info($request);
        $mail_info = $request->mail_info;
        $customer_email = $mail_info['customer_email'];
        $clinic_info = $mail_info['clinic_info'];
        if($this->email($customer_email, $mail_info['customer_first_name'], $mail_info['content']))
             return 'success';
        return 'failed';
    }
    public function email($target_email, $name, $body)
    {
        //$target_email = 'beautisong@hotmail.com';
        //$target_email = 'dmitriydevop@hotmail.com';
        $subject = "予約管理システム";
        $content = [
            'name' => $name,
            'subject' => $subject,
            'body' => $body
        ];
        Mail::to($target_email)->send(new OrderReserved($content));
        if(count(Mail::failures()) > 0){
            $errors = 'Failed to send password reset email, please try again.';
            Log::info($errors);
            return false;
        }
        //return (new OrderReserved($content));
        return true;
    }
    // public function downloadpdf(Request $request)
    // {
    //     $file_name = $request->filename;
    //     // Log::info($file_name);
    //     // $pathToFile = storage_path('app/pdf1.pdf');
    //     // $realpath = str_replace("\\","/", $pathToFile);

    //     // Log::info($realpath);

    //     $headers = [
    //         'Content-Type'=>'application/pdf',
    //     ];
    //     $file = public_path('pdf/' . $file_name);
    //     //return Response::download($file, 'filename.pdf', $headers);
    //     //return response()->download($realpath,"pdf1.pdf",$headers);
    //     return Storage::download('pdf1.pdf','pdf1.pdf',$headers);
    // }

    public function download(Request $request)
    {
        // $file_name = $request->filename;
        // Log::info($file_name);
        //PDF file is stored under project/public/download/info.pdf
        $file= public_path(). "/download/info.pdf";
        Log::info($file); 
        $headers = array(
                'Content-Type: application/pdf',
                );

        return Response::download($file, 'filename.pdf', $headers);
    }

    public function getDocument()
    {       

        $filePath = public_path(). "/download/info.pdf";
        $realpath = str_replace("\\","/", $filePath);
        Log::info($realpath);
        // file not found
        if( ! Storage::exists($realpath) ) {
            abort(404);
        }

        $pdfContent = Storage::get($realpath);

        // for pdf, it will be 'application/pdf'
        $type       = Storage::mimeType($realpath);
        $fileName   = Storage::name($realpath);

        return Response::make($pdfContent, 200, [
        'Content-Type'        => $type,
        'Content-Disposition' => 'inline; filename="'.$fileName.'"'
        ]);
    }
}

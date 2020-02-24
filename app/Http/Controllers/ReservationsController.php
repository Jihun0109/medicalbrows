<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaff;
use App\TblCustomer;
use App\TblOrder;
use App\TblOrderHistory;
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

    // 해당 cell(order_history)를 클릭했을 때 가능한 메뉴목록을 리턴
    public function menu_list(Request $request)
    {
        $rank_id = $request->rank_id;
        $selected_date = $request->date;
        $menu_list = DB::table('tbl_menus')->
            where(function($query) use ($selected_date) {
                $query->where(function($query) use ($selected_date){
                    $query->where('start_time', '<=', $selected_date)->where('end_time','>=', $selected_date);
                })->
                orWhere(function($query) use ($selected_date) {
                    $query->where('start_time', '<=', $selected_date)->whereNull('end_time');
                });
            })->
            where(['tbl_menus.is_deleted'=>0, 'tbl_menus.rank_id'=>$rank_id])->
            select('tbl_menus.id as menu_id','tbl_menus.name')->
            get();
        // $menu_list = DB::table('tbl_menus')
        //     ->where([['tbl_menus.is_deleted', 0], ['tbl_menus.rank_id', $rank_id]])
        //     ->select('tbl_menus.id as menu_id','tbl_menus.name')
        //     ->get();
        return $menu_list;
    }
    
    // 해당한 상담원 목록 리턴
    public function counselor_list(Request $request)
    {        
        Log::Info($request);
        $clinic_id = $request->clinic_id;
        $selected_date = date('Y-m-d',strtotime($request->date));
        $rank_schedule_id = $request->rank_schedule_id;
        $order_history_id = $request->order_history_id;
        $res = [];
        //get start time from rank_schedule_id
        $start_time = DB::table('tbl_rank_schedules')
            ->where([['tbl_rank_schedules.is_deleted', 0], ['tbl_rank_schedules.id', $rank_schedule_id]])
            ->select(DB::raw('HOUR(tbl_rank_schedules.start_time) as start_hour'))      
            ->get();


        $possible_endtime_addr = [ 10, 12, 15, 17 ];
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
            $temp = [];
            // 상담원이 인터뷰어로 예약되여 있으면 그 시간을 리턴.
            $remove_time_with_order = DB::table('tbl_order_histories')
                            ->where([['is_deleted', 0],['interviewer_id',$counselor_list[$i]->interviewer_id],['order_date', $selected_date]])
                            ->select('id', DB::raw('HOUR(tbl_order_histories.interview_end) as end_hour'))
                            ->get();
            $bflag = false;
            for( $j = 0; $j < sizeof($remove_time_with_order); $j++ ){
                if($remove_time_with_order[$j]->end_hour === $min && $order_history_id != $remove_time_with_order[$j]->id){
                    Log::info($remove_time_with_order[$j]->id);
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
       
        Log::info($res);
        return $res;
    }

    public function staff_rank_list(Request $request)
    {        
        $clinic_id = $request->clinic_id;
        $selected_date = date('Y-m-d',strtotime($request->date));
        $res = [];
        //query
        $staff_rank_names = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id')  
                ->join('tbl_shift_histories', 'tbl_shift_histories.staff_id','tbl_staffs.id')
                ->join('tbl_ranks', 'tbl_ranks.id','tbl_staff_ranks.rank_id')
                ->select('tbl_staffs.full_name','tbl_staff_ranks.rank_id', 'tbl_ranks.short_name','tbl_ranks.name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id], ['tbl_shift_histories.date', $selected_date]])  
                ->get();
        //return $staff_rank_names;
        for ( $i = 0; $i < sizeof($staff_rank_names); $i++ ){
            $temp = [];
            $temp = array('id'=> $i, 'name' => ($staff_rank_names[$i]->full_name.'('.$staff_rank_names[$i]->name).')');
            array_push($res, $temp);
        }        
        return $res;
    }

    // 해당 클리닉의 스타프목록을 얻어온다.
    public function staff_list(Request $request)
    {
        $start_tick = microtime(true);
        $clinic_id = $request->clinic_id; // 지정된 클리닉 아이디
        $selected_date = date('Y-m-d',strtotime($request->date)); // 현재선택된 날자 2020-02-20 형식 
        
        $staff_rank_with_schedules = [];

        // 스타프 staff_id, full_name, rank_id, rank_name, rank_short_name, clinic_name 얻는다.
        $staff_rank_names = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id')  
                ->join('tbl_ranks', 'tbl_ranks.id','tbl_staff_ranks.rank_id')
                ->join('tbl_clinics', 'tbl_clinics.id','tbl_staffs.clinic_id')
                ->select('tbl_staffs.id as staff_id','tbl_staffs.full_name','tbl_staff_ranks.rank_id', 'tbl_ranks.short_name','tbl_ranks.name','tbl_clinics.name as clinic_name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id]])
                ->orderBy('rank_id','asc')
                ->get();

        // 매 스타프에 대해서 스타프이름, 랭크략어, 랭크스케쥴목록을 반환
        for ( $i = 0; $i < sizeof($staff_rank_names); $i++ ){
            $temp = [];

            // Shift table 조회
            if (sizeof(DB::table("tbl_shift_histories")->where(['staff_id'=>$staff_rank_names[$i]->staff_id, 'date'=>$selected_date])->get()) > 0)
                continue;

            $rank_schedule = DB::table('tbl_rank_schedules')
                    ->where([['tbl_rank_schedules.is_deleted', 0], ['tbl_rank_schedules.rank_id', $staff_rank_names[$i]->rank_id]])
                    //->where('part_id',1) // アイブロウ
                    ->select(DB::raw('id,HOUR(tbl_rank_schedules.start_time) as start_hour, HOUR(tbl_rank_schedules.end_time) as end_hour, MINUTE(tbl_rank_schedules.start_time) as start_minute, MINUTE(tbl_rank_schedules.end_time) as end_minute'))       
                    ->get();
            $temp = array(
                        'staff_id'=>$staff_rank_names[$i]->staff_id,
                        'clinic_name'=>$staff_rank_names[$i]->clinic_name,
                        'rank_id'=>$staff_rank_names[$i]->rank_id,
                        'staff_name' => $staff_rank_names[$i]->full_name, 
                        'rank_name'=> $staff_rank_names[$i]->short_name,
                        'rank_full_name'=> $staff_rank_names[$i]->name,
                        'time_schedule'=> $rank_schedule
                    );
            array_push($staff_rank_with_schedules, $temp);
        }
        //Log::info($staff_rank_with_schedules);
        $cell_width = 3;
        $cell_height = 4;
        
        $staff = [];
        $layout = [];

        $idx_arr = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

        //first grid cell (Grid)
        array_push($staff, (object)[
            'x' => 0,
            'y' => 0,
            'w' => $cell_width,
            'h' => 2,
            'i' => "",
            'static' =>  true,
            'selectable' => false,
            'time' => '',
            'is_new' => '',
            'staff_rank' => '',
        ]);

        // 시간대렬 (Grid)
        for ( $i = 0; $i < sizeof($idx_arr); $i++ ){
            $content = $idx_arr[$i].':00';
            array_push($layout, (object)[
                'x' => 0,
                'y' => $i * $cell_height,
                'w' => $cell_width,
                'h' => $cell_height,
                'i' => '<p style="color:red ">'.$content.'</p>',
                'static' =>  false,
                'selectable' => false,
                'time' => '',
                'is_new' => '',
                'staff_rank' => '',
            ]);
        }

        // 스타프이름 & 랭크 (Grid)
        for ( $i = 0; $i < sizeof($staff_rank_with_schedules); $i++ ) {
            //addition of staff names
            array_push($staff, (object)[
                'x' => ($i + 1)* $cell_width,
                'y' => 0,
                'w' => $cell_width,
                'h' => 1,
                'i' => $staff_rank_with_schedules[$i]['staff_name'],
                'static' =>  true,
                'selectable' => false,
                'time' => '',
                'is_new' => '',
                'staff_rank' => '',
            ]);
            //addition of rank names
            array_push($staff, (object)[
                'x' => ($i + 1)* $cell_width,
                'y' => 1,
                'w' => $cell_width,
                'h' => 1,
                'i' => $staff_rank_with_schedules[$i]['rank_name'],
                'static' =>  true,
                'selectable' => false,
                'time' => '',
                'is_new' => '',
                'staff_rank' => '',
            ]);
            $total_height_arr = [];
            for ($k=0; $k < $cell_height * sizeof($idx_arr); $k++)
                array_push($total_height_arr, 0);

            // 랭크스케쥴 순환. 스케쥴테블에서 날자검사해야 한다.
            foreach( $staff_rank_with_schedules[$i]['time_schedule'] as $index=>$schedule ) {
                $cur_schedule = get_object_vars($schedule);
                // 매 스타프쎌의 y좌표
                $y = $cell_height * ($cur_schedule['start_hour'] - $idx_arr[0]) + (int)($cur_schedule['start_minute'] / 15);
                // 매 스타프쎌의 height
                $h = $cell_height * ($cur_schedule['end_hour'] - $cur_schedule['start_hour']);
                $sign = $cur_schedule['end_minute'] > $cur_schedule['start_minute'] ? 1 : -1;
                $h += (int)(abs($cur_schedule['end_minute'] - $cur_schedule['start_minute']) / 15) * $sign;
                // 공백쎌은 0 아니면 1
                for ($k=0; $k<$h; $k++) $total_height_arr[$y+$k] = 1;
                $order_history; // 지정된 날자의 해당 schedule타임에 설정된 order_history정보를 검사하고 있으면 필요한 자료 뽑는다.
                if($staff_rank_with_schedules[$i]['rank_name'] == 'カウゼ')
                {
                    // order_history 변수에 order_history정보와 customer정보 order_serial_id정보를 
                    $order_history = DB::table('tbl_order_histories')->
                    join('tbl_orders', 'tbl_orders.id','tbl_order_histories.order_id')->
                    join('tbl_customers','tbl_customers.id','tbl_orders.customer_id')->
                    select('tbl_order_histories.*','tbl_orders.order_serial_id','tbl_orders.order_route',
                            'tbl_customers.id as customer_id','tbl_customers.first_name', 'tbl_customers.last_name',
                            'tbl_customers.phonenumber','tbl_customers.birthday','tbl_orders.note')->
                    where(['staff_id' => $staff_rank_with_schedules[$i]['staff_id'],'rank_schedule_id'=>$cur_schedule['id'],['tbl_order_histories.order_date', $selected_date]])->
                    first();
                }else{ // 상담원이 아닌 의사인경우 메뉴정보도 같이 얻는다,
                    $order_history = DB::table('tbl_order_histories')->
                    join('tbl_orders', 'tbl_orders.id','tbl_order_histories.order_id')->
                    join('tbl_menus', 'tbl_menus.id','tbl_orders.menu_id')->
                    join('tbl_customers','tbl_customers.id','tbl_orders.customer_id')->
                    select('tbl_order_histories.*','tbl_orders.order_serial_id','tbl_orders.order_route','tbl_menus.id as menu_id','tbl_menus.name as menu_name',
                            'tbl_customers.id as customer_id','tbl_customers.first_name', 'tbl_customers.last_name',
                            'tbl_customers.phonenumber','tbl_customers.birthday','tbl_orders.note')->
                    where(['staff_id' => $staff_rank_with_schedules[$i]['staff_id'],'rank_schedule_id'=>$cur_schedule['id'],['tbl_order_histories.order_date', $selected_date]])->
                    first();
                }
                $start_minute = $cur_schedule['start_minute'];
                $end_minute = $cur_schedule['end_minute'];
                if($start_minute == 0){
                    $start_minute = '00';
                }
                if($end_minute == 0){
                    $end_minute = '00';
                }
                $time = $cur_schedule['start_hour'].":".$start_minute.'~'. 
                        $cur_schedule['end_hour'].":".$end_minute;
                /*
                【新規】 : is_new
                010-XXXXXXXX : serial_id
                プレミアムハナコ : customer_name
                指名あり : choosed
                アイブロウ2回 1/2 : menu

                カウセ     : counselor
                9:20～10:00 : time
                加野  : counselor_name
                */
                //order_type(xin,zai,coun)에 따라 i값을 결정한다
                $content = '';
                $menu_id = '';
                $menu_name = '';
                $order_type = '';
                $interviewer = [];
                if ($order_history && $order_history->interviewer_id)
                    $interviewer = TblStaff::where('id', $order_history->interviewer_id)->first();

                if(is_null($order_history)){
                    if($staff_rank_with_schedules[$i]['rank_name'] == 'カウゼ')
                    {
                        $content = '新';
                        $order_type = 'カウンセ';
                    }
                    else{
                        $content = '再';
                        $order_type = '新規';
                    }
                }else{
                    Log::info($order_history->id);
                    $order_type = $order_history->order_type;
                    if($order_history->order_type != "再診")
                    {
                        // 상담원인경우 해당 정보를 입력
                        if($staff_rank_with_schedules[$i]['rank_name'] == 'カウゼ')
                        {
                            $content = '<div>【'.$order_history->order_type.'】</div><div>'.$order_history->order_serial_id.'</div><div>'.$order_history->first_name.'</div><div>';
                        }
                        else{
                            // 특정 랭크 (NA, T) 에 대해서는 상담원을 겸하기 때문에 상담원 정보 뽑을 필요없어.
                            if($staff_rank_with_schedules[$i]['rank_name']  == 'NA' || $staff_rank_with_schedules[$i]['rank_name']  == 'T')
                            {
                                $content = '<div>【'.$order_history->order_type.'】</div><div>'.$order_history->order_serial_id.'</div><div>'.$order_history->first_name.'</div><div>指名:'.$order_history->staff_choosed.'</div><div>'.$order_history->menu_name.'</div>';
                            }
                            else{ // 나머지 시술은 상담원정보를 뽑아야 한다.
                                $counselor_name = $interviewer?$interviewer->full_name:'';
                                $content = '<div>【'.$order_history->order_type.'】</div><div>'.$order_history->order_serial_id.'</div><div>'.$order_history->first_name.'</div><div>指名:'.$order_history->staff_choosed.'</div><div>'.$order_history->menu_name.'</div><br><div>カウセ</div><div>'.date('H:i', strtotime($order_history->interview_start)).'~'.date('H:i', strtotime($order_history->interview_end)).'</div><div>'.$counselor_name.'</div>';
                            }
                            $menu_id = $order_history->menu_id;
                            $menu_name = $order_history->menu_name;
                        }
    
                    }
                    else{
                        $content = '<div>【'.$order_history->order_type.'】</div><div>'.$order_history->order_serial_id.'</div><div>'.$order_history->first_name.'</div><div>指名:'.$order_history->staff_choosed.'</div><div>'.$order_history->menu_name.'</div>';
                        $menu_id = $order_history->menu_id;
                        $menu_name = $order_history->menu_name;
                    }
                }
                array_push($layout, (object)[
                    'x' => ($i + 1) * $cell_width,
                    'y' => $y,
                    'w' => $cell_width,
                    'h' => $h,
                    'i' => $content,
                    'static' =>  true,
                    'selectable' => true,
                    'time' => $time,
                    'is_new' => "新",
                    'staff_rank' => "",
                    'order_status' => $order_history?$this->getOrderStatus($order_history->status):"static",
                    'rank_schedule_id' => $cur_schedule['id'],
                    'staff_id' => $staff_rank_with_schedules[$i]['staff_id'],
                    'staff_name' => $staff_rank_with_schedules[$i]['staff_name'],
                    'rank_id' => $staff_rank_with_schedules[$i]['rank_id'],
                    'rank_name' => $staff_rank_with_schedules[$i]['rank_name'],
                    'rank_full_name' => $staff_rank_with_schedules[$i]['rank_full_name'],
                    'clinic_name' => $staff_rank_with_schedules[$i]['clinic_name'],

                    'order_serial_id' => $order_history?$order_history->order_serial_id:'',
                    'order_history_id' => $order_history?$order_history->id:0,
                    'order_type' =>  $order_type,
                    'staff_choosed' => $order_history?$order_history->staff_choosed:'あり',
                    'menu_id' => $menu_id,
                    'menu_name' => $menu_name,

                    'customer_first_name' => $order_history?$order_history->first_name:'',
                    'customer_last_name' => $order_history?$order_history->last_name:'',
                    'customer_phonenumber' => $order_history?$order_history->phonenumber:'',
                    'customer_birthday' => $order_history?$order_history->birthday:'',
                    'order_route' => $order_history?$order_history->order_route:'',

                    'note' => $order_history?$order_history->note:'',
                    'interviewer_id' => $interviewer?$interviewer->id:'',
                    'interviewer_name' => $interviewer?$interviewer->full_name:'',
                ]);
            }

            // 공백쎌추가
            $max_height = $cell_height * sizeof($idx_arr);
            $start = -1;
            $draw_flag = false;
            $h = 0;
            for ($k=0; $k < $max_height; $k++){
                if ($total_height_arr[$k] == 0){
                    if ($start == -1) $start = $k;
                    $h += 1;
                }

                if ($start > -1 && ($total_height_arr[$k] == 1 || ($k + 1) % $cell_height == 0))
                    $draw_flag = true;

                if ($draw_flag){
                    array_push($layout, (object)[
                        'x' => ($i + 1) * $cell_width,
                        'y' => $start,
                        'w' => $cell_width,
                        'h' => $h,
                        'i' => "",
                        'static' =>  false,
                        'selectable' => false,
                    ]);
                    $draw_flag = false;
                    $start = -1;
                    $h = 0;
                }
            }
        }
        $ret = array('count'=>sizeof($staff_rank_with_schedules), 'staff_layout' => $staff, 'content_layout'=> $layout);


        Log::info("staff_list function exection time: ".(microtime(true)-$start_tick)); 
        return $ret;
    }

    public function getOrderStatus($type_value){        
        $status_arr = ['static', 'neworder', 'oldorder', 'grayconselor', 'cancelorder'];
        $color_class = $status_arr[$type_value];
        return $color_class;
    }

    public function orderCreate(Request $request)
    {
        $start_tick = microtime(true);
        $payLoad = json_decode(request()->getContent(), true);  
        //Log::error($payLoad);
        //return;
        $order_serial_id = $payLoad['order_serial_id'];
        // First create Customer if order_type is 新規 or カウンセ
        if ($payLoad['order_type'] != "再診")
        {
            //confirm field 
            $this->validate($request, [
                'first_name' => 'string|max:30',
                'last_name' => 'string|max:30',
                'birthday' => 'required|date', 
                'phonenumber' => 'string|max:30',
                //'order_route' => 'string|max:30',
            ]); 

            $customer = TblCustomer::create([
                //'email' => '',
                //'gender' => '',
                'first_name' => $payLoad['first_name'],
                'last_name' => $payLoad['last_name'],
                //'address' => '',
                'phonenumber' => $payLoad['phonenumber'],
                'birthday' => $payLoad['birthday'],
                'is_deleted' => 0
            ]);
            // Second create Order 
            $order_serial_id = sprintf('%s-%06d', time(), $customer->id);

            if ($payLoad['order_type'] == "新規"){
                $order = TblOrder::create([
                    'order_serial_id' => $order_serial_id,
                    'customer_id' => $customer->id,
                    //'customer_status' => 1,   // 의미모름
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax_id' => 0,
                    'total' => 0,
                    'note' => $payLoad['note'],
                    'order_date' => $payLoad['item']['date'],
                    //'cancel_date' => '',
                    'menu_id' => $payLoad['menu_id'],
                    'order_route' => $payLoad['order_route'],
                    'is_deleted' => 0,
                ]);

                // 우에서 금방 창조했으므로 오브젝트 아래 2오브젝트는 존재함. 따라서 쿼리 할필요는 없을거 같음.
                //$order = TblOrder::where(['order_serial_id'=>$order_serial_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
                //$customer = TblCustomer::where(['id'=>$order->customer_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
                if($payLoad['item']['rank_name']  != 'NA' && $payLoad['item']['rank_name']  != 'T')
                {
                    $order_history = TblOrderHistory::create([
                        'staff_id' => $payLoad['counselor']['interviewer_id'],
                        'rank_id' => 9,//counselor_rank_id
                        'order_id' => $order->id,
                        'status' => 0,//$payLoad['item']['order_status'],//1,//
                        'staff_choosed' => $payLoad['stuff_choosed'],
                        'rank_schedule_id' => $payLoad['counselor']['interviewer_rank_schedule_id'],
                        'order_type' => $payLoad['order_type'],
                        'order_date' => $payLoad['item']['date'],
                        'order_route' => $payLoad['order_route'],
                        'is_deleted' => 0
                    ]);
                }
            }
            else{ //カウンセ
                $order = TblOrder::create([
                    'order_serial_id' => $order_serial_id,
                    'customer_id' => $customer->id,
                    //'customer_status' => 1,   // 의미모름
                    'subtotal' => 0,
                    'discount' => 0,
                    'tax_id' => 0,
                    'total' => 0,
                    'note' => $payLoad['note'],
                    'order_date' => $payLoad['item']['date'],
                    //'cancel_date' => '',
                    //'menu_id' => '',
                    'order_route' => $payLoad['order_route'],
                    'is_deleted' => 0,
                ]); 
            }
        }

        // Third create Order history        
        $order = TblOrder::where(['order_serial_id'=>$order_serial_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        //재진인경우 예약ID가 존재하지 않으면 오유통보 현시 
        if($payLoad['order_type'] == "再診" && is_null($order)){
            return 0;
        }
        $customer = TblCustomer::where(['id'=>$order->customer_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        
        if ($payLoad['order_type'] == "新規"){
            if($payLoad['item']['rank_name']  == 'NA' || $payLoad['item']['rank_name']  == 'T')
            {
                $order_history = TblOrderHistory::create([
                    'staff_id' => $payLoad['item']['staff_id'],
                    'rank_id' => $payLoad['item']['rank_id'],
                    'order_id' => $order->id,
                    'status' => 0,//$payLoad['item']['order_status'],//1,//
                    'staff_choosed' => $payLoad['stuff_choosed'],
                    'rank_schedule_id' => $payLoad['item']['rank_schedule_id'],
                    'order_type' => $payLoad['order_type'],
                    'order_date' => $payLoad['item']['date'],
                    'menu_id' => $payLoad['menu_id'],
                    'order_route' => $payLoad['order_route'],
                    'is_deleted' => 0
                ]);
            }else{
                $order_history = TblOrderHistory::create([
                    'staff_id' => $payLoad['item']['staff_id'],
                    'rank_id' => $payLoad['item']['rank_id'],
                    'order_id' => $order->id,
                    'interviewer_id' => $payLoad['counselor']['interviewer_id'],
                    'interview_start' => $payLoad['counselor']['interview_start'],
                    'interview_end' => $payLoad['counselor']['interview_end'],
                    'status' => 0,//$payLoad['item']['order_status'],//1,//
                    'staff_choosed' => $payLoad['stuff_choosed'],
                    'rank_schedule_id' => $payLoad['item']['rank_schedule_id'],
                    'order_type' => $payLoad['order_type'],
                    'order_date' => $payLoad['item']['date'],
                    'menu_id' => $payLoad['menu_id'],
                    'order_route' => $payLoad['order_route'],
                    'is_deleted' => 0
                ]);
            }
        }
        else if ($payLoad['order_type'] == "カウンセ"){
            $schedule_info = DB::table('tbl_rank_schedules')->where('id', $payLoad['item']['rank_schedule_id'])->first();
            $order_history = TblOrderHistory::create([
                'staff_id' => $payLoad['item']['staff_id'],
                'rank_id' => $payLoad['item']['rank_id'],
                'order_id' => $order->id,
                'interviewer_id' => $payLoad['item']['staff_id'],
                'interview_start' => $schedule_info->start_time,
                'interview_end' => $schedule_info->end_time,
                'status' => 0,//$payLoad['item']['order_status'],//1,//
                'staff_choosed' => $payLoad['stuff_choosed'],
                'rank_schedule_id' => $payLoad['item']['rank_schedule_id'],
                'order_type' => $payLoad['order_type'],
                'order_date' => $payLoad['item']['date'],
                'order_route' => $payLoad['order_route'],
                'is_deleted' => 0
            ]);
        }
        else{//再診
            $order_history = TblOrderHistory::create([
                'staff_id' => $payLoad['item']['staff_id'],
                'rank_id' => $payLoad['item']['rank_id'],
                'order_id' => $order->id,
                'status' => 0,//$payLoad['item']['order_status'],//1,//
                'staff_choosed' => $payLoad['stuff_choosed'],
                'rank_schedule_id' => $payLoad['item']['rank_schedule_id'],
                'order_type' => $payLoad['order_type'],
                'order_date' => $payLoad['item']['date'],
                'menu_id' => $payLoad['menu_id'],
                'order_route' => $payLoad['order_route'],
                'is_deleted' => 0
            ]);
        }
    
        $menu_info = DB::table('tbl_menus')->where('id', $order_history->menu_id)->first();
        $rank_info = DB::table('tbl_ranks')->where('id', $order_history->rank_id)->first();
        $staff_info = DB::table('tbl_staffs')->where('id',$order_history->staff_id)->first();

        $ret = $payLoad['item'];
        $ret['order_history_id'] = $order_history->id;
        $ret['order_serial_id'] = $order_serial_id;
        $ret['order_status'] = 'static';//$order_history?$this->getOrderStatus($order_history->status):"static";
        $ret['order_type'] = $order_history->order_type;
        $ret['order_route'] = $order_history->order_route;
        $ret['rank_id'] = $order_history->rank_id;
        $ret['staff_choosed'] = $order_history->staff_choosed;

        $ret['staff_name'] = $staff_info->full_name;
        $ret['rank_name'] = $rank_info->short_name;
        $ret['clinic_name'] = '';

        if ($payLoad['order_type'] != "カウンセ"){
            $ret['menu_id'] = $menu_info->id;
            $ret['menu_name'] = $menu_info->name;
        }        
        //모든 item정보는 자료기지로부터 다시 가져오는것이 좋다. 재진인 경우 입력 form의  customer정보를 돌려주기때문이다.
        $ret['customer_first_name'] = $customer->first_name;//$payLoad['first_name'];
        $ret['customer_last_name']= $customer->last_name;//$payLoad['last_name'];
        $ret['customer_phonenumber']= $customer->phonenumber;//$payLoad['phonenumber'];
        $ret['customer_birthday']= $customer->birthday;//$payLoad['birthday'];

        $ret['note'] = $order->note;        
        $ret['interviewer_id'] = '';
        $ret['interviewer_name'] = '';
        //$ret['i'] = $order_serial_id.$ret['customer_first_name'];
        $ret['i'] =  '<div>【再診】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><div>';
        $ret_array = [];

        if ($payLoad['order_type'] != "再診"){
            if ($payLoad['order_type'] == "新規"){
                if($payLoad['item']['rank_name']  == 'NA' || $payLoad['item']['rank_name']  == 'T')
                {
                    $ret['i'] =  '<div>【新規】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><div>';
                }else{
                    $ret['i'] =  '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><br><div>カウセ</div><div>'.$payLoad['counselor']['interview_start'].'~'.$payLoad['counselor']['interview_end'].'</div><div>'.$payLoad['counselor']['counselor_name'].'</div>';

                    $ret_new = $ret;
                    $ret_new['x'] = $payLoad['counselor']['x'];
                    $ret_new['y'] = $payLoad['counselor']['y'];
                    $ret_new['i'] = '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>カウセ</div><div>';
                    array_push($ret_array, $ret_new);

                    $ret['interviewer_id'] = $payLoad['counselor']['interviewer_id'];
                    $ret['interviewer_name'] = $payLoad['counselor']['counselor_name'];
                    $ret['old_itvr_x'] = $payLoad['counselor']['x'];
                    $ret['old_itvr_y'] = $payLoad['counselor']['y'];
                }
            } 
            else{ // カウンセ
                $ret['i'] =  '<div>【カウンセリ】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div>';
            }
        }

        array_push($ret_array, $ret);
        return $ret_array;
    }
    
    public function orderStatusUpdate(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        $order_history_id = $payLoad['item']['order_history_id'];
        $idx = $payLoad['statusIdx'] + 1;
        $status_arr = ['static','neworder','oldorder','grayconselor','cancelorder'];
        $order_history_ret = TblOrderHistory::where(['id'=>$order_history_id, 'is_deleted'=>0])
                            ->update([
                                'status' =>  $idx,
                            ]);
        $payLoad['item']['order_status'] = $status_arr[$idx];
        return $payLoad;
    }
    
    // 예약수정 함수
    public function orderUpdate(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        Log::error($payLoad);
        
        //confirm field 
        $this->validate($request, [
            'first_name' => 'string|max:30',
            'last_name' => 'string|max:30',
            'birthday' => 'required|date', 
            'phonenumber' => 'string|max:30',
            //'order_route' => 'string|max:30',
        ]); 
        $order_serial_id = $payLoad['order_serial_id'];

        //update order table
        $order = TblOrder::where(['order_serial_id'=>$order_serial_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        
        $order->menu_id = $payLoad['menu_id'];
        $order->note = $payLoad['note'];
        $order->order_route = $payLoad['order_route'];        
        $order->save();
        
        //update customer table
        $customer = TblCustomer::where('id', $order->customer_id)->first();
        $customer->first_name = $payLoad['first_name'];
        $customer->last_name = $payLoad['last_name'];
        $customer->phonenumber = $payLoad['phonenumber'];
        $customer->birthday = $payLoad['birthday'];
        $customer->save();
        
        $order_history = TblOrderHistory::where('id',$payLoad['item']['order_history_id'])->first();
        $bChangedInterviewer = false;
        if ($order_history->interviewer_id && $payLoad['counselor']){
            if ($order_history->interviewer_id !== $payLoad['counselor']['interviewer_id']){
                $interviewer = TblOrderHistory::where(['order_id'=>$order_history->order_id, 
                        'staff_id'=>$order_history->interviewer_id,
                        'rank_schedule_id'=>$payLoad['counselor']['interviewer_rank_schedule_id']])->first();
                $interviewer->staff_id = $payLoad['counselor']['interviewer_id'];
                $interviewer->save();

                $order_history->interviewer_id = $payLoad['counselor']['interviewer_id'];
                $bChangedInterviewer = true;
            }
        }
        $order_history->staff_id = $payLoad['item']['staff_id'];
        $order_history->rank_id = $payLoad['item']['rank_id'];
        $order_history->order_id = $order->id;
        $order_history->staff_choosed = $payLoad['stuff_choosed'];
        $order_history->rank_schedule_id = $payLoad['item']['rank_schedule_id'];
        $order_history->order_type = $payLoad['order_type'];
        $order_history->save();

        $menu_info = DB::table('tbl_menus')->where('id', $order->menu_id)->first();
        $rank_info = DB::table('tbl_ranks')->where('id', $order_history->rank_id)->first();
        $staff_info = DB::table('tbl_staffs')->where('id',$order_history->staff_id)->first();
        $ret = $payLoad['item'];
        $ret['order_history_id'] = $order_history->id;
        $ret['order_serial_id'] = $order_serial_id;
        $ret['order_status'] = $order_history?$this->getOrderStatus($order_history->status):"static";
        $ret['order_type'] = $order_history->order_type;
        $ret['rank_id'] = $order_history->rank_id;
        $ret['staff_choosed'] = $order_history->staff_choosed;

        $ret['staff_name'] = $staff_info->full_name;
        $ret['rank_name'] = $rank_info->name;
        $ret['clinic_name'] = '';
        $ret['menu_id'] = $menu_info->id;
        $ret['menu_name'] = $menu_info->name;

        $ret['customer_first_name'] =$payLoad['first_name'];
        $ret['customer_last_name']= $payLoad['last_name'];
        $ret['customer_phonenumber']= $payLoad['phonenumber'];
        $ret['customer_birthday']= $payLoad['birthday'];
        $ret['order_route'] = $order->order_route;
        $ret['note'] = $payLoad['note'];
        //$ret['i'] = $order_serial_id.$ret['customer_first_name'];
        $ret['i'] =  '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><br><div>カウセ</div><div>'.$ret['time'].'</div><div>'.$ret['staff_choosed'].'</div>' ;

        $ret_array = [];

        if ($payLoad['order_type'] != "再診"){
            if ($payLoad['order_type'] == "新規"){
                if($payLoad['item']['rank_name']  == 'NA' || $payLoad['item']['rank_name']  == 'T')
                {
                    $ret['i'] =  '<div>【新規】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><div>';
                }else{
                    $ret_new = $ret;
                    $ret_new['x'] = $payLoad['counselor']['x'];
                    $ret_new['y'] = $payLoad['counselor']['y'];
                    $ret_new['i'] = '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>';
                    array_push($ret_array, $ret_new);
                    if ($bChangedInterviewer){
                        $ret_old = [];
                        $ret_old['x'] = $payLoad['old_itvr_x'];
                        $ret_old['y'] = $payLoad['old_itvr_y'];
                        $ret_old['order_serial_id'] = $ret_old['order_history_id'] = '';
                        $ret_old['i'] = '新';
                        array_push($ret_array, $ret_old);
                    }
                    $ret['i'] =  '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><br><div>カウセ</div><div>'.$payLoad['counselor']['interview_start'].'~'.$payLoad['counselor']['interview_end'].'</div><div>'.$payLoad['counselor']['counselor_name'].'</div>';
                    $ret['interviewer_id'] = $payLoad['counselor']['interviewer_id'];
                    $ret['interviewer_name'] = $payLoad['counselor']['counselor_name'];
                    $ret['old_itvr_x'] = $payLoad['counselor']['x'];
                    $ret['old_itvr_y'] = $payLoad['counselor']['y'];
                }
            } 
            else{ // カウンセ
                $ret['i'] =  '<div>【カウンセリ】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div>';
            }
        }

        array_push($ret_array, $ret);
        return $ret_array;
    }
}
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

    public function counselor_list(Request $request)
    {        
        $clinic_id = $request->clinic_id;
        //query
        $counselor_list = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id')                
                //->select('tbl_staffs.full_name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id], ['tbl_staff_ranks.rank_id', 9]])  
                ->get();
     
        return $counselor_list;
    }

    public function staff_rank_list(Request $request)
    {        
        $clinic_id = $request->clinic_id;
        $res = [];

        //query
        $staff_rank_names = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id')  
                ->join('tbl_ranks', 'tbl_ranks.id','tbl_staff_ranks.rank_id')    
                ->select('tbl_staffs.full_name','tbl_staff_ranks.rank_id', 'tbl_ranks.short_name','tbl_ranks.name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id]])  
                ->get();
        //return $staff_rank_names;
        for ( $i = 0; $i < sizeof($staff_rank_names); $i++ ){
            $temp = [];
            $temp = array('id'=> $i, 'name' => ($staff_rank_names[$i]->full_name.$staff_rank_names[$i]->name));
            array_push($res, $temp);
        }        
        return $res;
    }

    public function staff_list(Request $request)
    {        
        $clinic_id = $request->clinic_id;
        $staff_rank_with_schedules = [];

        // 스타프 staff_id, full_name, rank_id, rank_name, rank_short_name 얻는다.
        $staff_rank_names = DB::table('tbl_staffs')
                ->join('tbl_staff_ranks', 'tbl_staff_ranks.staff_id','tbl_staffs.id')  
                ->join('tbl_ranks', 'tbl_ranks.id','tbl_staff_ranks.rank_id')
                ->join('tbl_clinics', 'tbl_clinics.id','tbl_staffs.clinic_id')
                ->select('tbl_staffs.id as staff_id','tbl_staffs.full_name','tbl_staff_ranks.rank_id', 'tbl_ranks.short_name','tbl_ranks.name','tbl_clinics.name as clinic_name')       
                ->where([['tbl_staffs.is_deleted', 0], ['tbl_staffs.clinic_id', $clinic_id]])  
                ->get();
       
        // 매 스타프에 대해서 스타프이름, 랭크략어, 랭크스케쥴목록을 반환
        for ( $i = 0; $i < sizeof($staff_rank_names); $i++ ){
            $temp = [];
            $rank_schedule = DB::table('tbl_rank_schedules')
                    ->where([['tbl_rank_schedules.is_deleted', 0], ['tbl_rank_schedules.rank_id', $staff_rank_names[$i]->rank_id]])  
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

        $cell_width = 3;
        $cell_height = 4;
        
        $staff = [];
        $layout = [];

        $idx_arr = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];

        //first grid cell
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

            // 랭크스케쥴 순환. 스케쥴테블에서 날자검사해야 한다. (요건 후에보자)
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

                $order_history = DB::table('tbl_order_histories')->
                    join('tbl_orders', 'tbl_orders.id','tbl_order_histories.order_id')->
                    join('tbl_menus', 'tbl_menus.id','tbl_orders.menu_id')->
                    join('tbl_customers','tbl_customers.id','tbl_orders.customer_id')->
                    select('tbl_order_histories.*','tbl_orders.order_serial_id','tbl_orders.order_route','tbl_menus.id as menu_id','tbl_menus.name as menu_name',
                            'tbl_customers.id as customer_id','tbl_customers.first_name', 'tbl_customers.last_name',
                            'tbl_customers.phonenumber','tbl_customers.birthday')->                    
                    where(['staff_id' => $staff_rank_with_schedules[$i]['staff_id'],'rank_schedule_id'=>$cur_schedule['id']])->
                    first();
            
                
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
                array_push($layout, (object)[
                    'x' => ($i + 1) * $cell_width,
                    'y' => $y,
                    'w' => $cell_width,
                    'h' => $h,
                    'i' => $order_history?'<div>【'.$order_history->order_type.'】</div><div>'.$order_history->order_serial_id.'</div><div>'.$order_history->first_name.'</div><div>指名:'.$order_history->staff_choosed.'</div><div>'.$order_history->menu_name.'</div><br><div>カウセ</div><div>'.$time.'</div><div>'.$order_history->staff_choosed.'</div>':"再",
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
                    'order_type' => $order_history?$order_history->order_type:'新規',
                    'staff_choosed' => $order_history?$order_history->staff_choosed:'あり',
                    'menu_id' => $order_history?$order_history->menu_id:'',
                    'menu_name' => $order_history?$order_history->menu_name:'',

                    'customer_first_name' => $order_history?$order_history->first_name:'',
                    'customer_last_name' => $order_history?$order_history->last_name:'',
                    'customer_phonenumber' => $order_history?$order_history->phonenumber:'',
                    'customer_birthday' => $order_history?$order_history->birthday:'',
                    'order_route' => $order_history?$order_history->order_route:'',
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
        return $ret;
    }

    public function getOrderStatus($type_value){
        $color_class = '';
        switch($type_value)
        {
            case 0:
                $color_class = 'static';  
                break;
            case 1:
                $color_class = 'neworder';  
                break;
            case 2:
                $color_class = 'oldorder';  
                break;
            case 3:
                $color_class = 'cancelorder';  
                break;
            case 4:
                $color_class = 'grayconselor';  
                break;
            default:
                $color_class = 'static';  
        }
        return $color_class;
    }

    public function orderCreate(Request $request)
    {
        Log::error($request);
        //confirm field 
        $this->validate($request, [
            'first_name' => 'string|max:30',
            'last_name' => 'string|max:30',
            'birthday' => 'required|date', 
            'phonenumber' => 'string|max:30',
            //'order_route' => 'string|max:30',            
        ]); 

        $payLoad = json_decode(request()->getContent(), true);        
        $order_serial_id = $payLoad['order_serial_id'];
        $order_status = 2;
        // First create Customer if order_type is 新規
        if ($payLoad['order_type'] == "新規")
        {
            $customer = TblCustomer::create([
                'first_name' => $payLoad['first_name'],
                'last_name' => $payLoad['last_name'],
                'phonenumber' => $payLoad['phonenumber'],
                'birthday' => $payLoad['birthday'],
                'is_deleted' => 0
            ]);
            // Second create Order 
            $order_serial_id = sprintf('%s-%06d', time(), $customer->id);

            $order = TblOrder::create([
                'order_serial_id' => $order_serial_id,
                'customer_id' => $customer->id,
                //'customer_status' => 1,   // 의미모름
                'subtotal' => 0,
                'discount' => 0,
                'total' => 0,
                'menu_id' => $payLoad['menu_id'],
                'note' => '',
                'order_route' => $payLoad['order_route'],
                'is_deleted' => 0,
            ]);
            $order_status = 1;
        }
        Log::error($order_serial_id);
        // Third create Order schedule        
        $order = TblOrder::where(['order_serial_id'=>$order_serial_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        Log::error($order);
        $customer = TblCustomer::where(['id'=>$order->customer_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        //재진인경우 예약ID가 존재하지 않으면 오유통보 현시 
        if($payLoad['order_type'] == "再診" && is_null($order)){
            //$this->validate($request, [
            //    'order_serial_id' => 'string|max:30',
            //]); 
            return 0;
        }
        $order_history = TblOrderHistory::create([
            'staff_id' => $payLoad['item']['staff_id'],
            'rank_id' => $payLoad['item']['rank_id'],
            'order_id' => $order->id,
            'status' => $order_status,//$payLoad['item']['order_status'],//1,//
            'staff_choosed' => $payLoad['stuff_choosed'],
            'rank_schedule_id' => $payLoad['item']['rank_schedule_id'],
            'order_type' => $payLoad['order_type'],
            'is_deleted' => 0
        ]);

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
        
        //모든 item정보는 자료기지로부터 다시 가져오는것이 좋다. 재진인 경우 입력 form의  customer정보를 돌려주기때문이다.
        $ret['customer_first_name'] = $customer->first_name;//$payLoad['first_name'];
        $ret['customer_last_name']= $customer->last_name;//$payLoad['last_name'];
        $ret['customer_phonenumber']= $customer->phonenumber;//$payLoad['phonenumber'];
        $ret['customer_birthday']= $customer->birthday;//$payLoad['birthday'];

        //$ret['i'] = $order_serial_id.$ret['customer_first_name'];
        $ret['i'] =  '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><br><div>カウセ</div><div>'.$ret['time'].'</div><div>'.$ret['staff_choosed'].'</div>' ;
        return $ret;
    }

    public function orderStatusUpdate(Request $request)
    {
        $payLoad = json_decode(request()->getContent(), true);
        $order_history_id = $payLoad['item']['order_history_id'];
        Log::error($payLoad);
        $status = 0;
        switch($payLoad['item']['order_status'])
        {
            case 'neworder':
                $status = 1;
                break;
            case 'oldorder':
                $status = 2;
                break;
            case 'cancelorder':
                $status = 3;
                break;
            case 'grayconselor':
                $status = 4;
                break;
        }
        $order_history_ret = TblOrderHistory::where(['id'=>$order_history_id, 'is_deleted'=>0])
                            ->update([
                                'status' =>  $status,
                            ]);
        return $payLoad;
    }

    public function orderUpdate(Request $request)
    {
        Log::error('update-log'.$request);
        return 0;
        //confirm field 
        $this->validate($request, [
            'first_name' => 'string|max:30',
            'last_name' => 'string|max:30',
            'birthday' => 'required|date', 
            'phonenumber' => 'string|max:30',
            //'order_route' => 'string|max:30',            
        ]); 

        $payLoad = json_decode(request()->getContent(), true);        
        $order_serial_id = $payLoad['order_serial_id'];
        $order_status = 2;
        if ($payLoad['order_type'] == "新規")
        {
            $order_status = 1;
        }
        //update order table
        $order_ret = DB::table('tbl_orders')
                            ->where('order_serial_id', $order_serial_id)
                            ->update(
                            [
                            //'customer_status' => 1,   // 의미모름
                            'subtotal' => 0,
                            'discount' => 0,
                            'total' => 0,
                            'menu_id' => $payLoad['menu_id'],
                            'note' => '',
                            'order_route' => $payLoad['order_route'],
                            'is_deleted' => 0,
                            ]);
        
        Log::error('order-log'.$order_ret);
        $order = TblOrder::where(['order_serial_id'=>$order_serial_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        Log::error('order-log'.$order);
        //update customer table
        $customer_ret = DB::table('tbl_customers')
                            ->where('id', $order->customer_id)
                            ->update([
                                'first_name' => $payLoad['first_name'],
                                'last_name' => $payLoad['last_name'],
                                'phonenumber' => $payLoad['phonenumber'],
                                'birthday' => $payLoad['birthday'],
                                'is_deleted' => 0
                            ]);

        // update order_history
        $customer = TblCustomer::where(['id'=>$order->customer_id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();
        Log::error('customer-log'.$customer);
        $order_history_ret = TblOrderHistory::where(['order_id'=>$order->id, 'is_deleted'=>0])
                                          ->update([
                                                'staff_id' => $payLoad['item']['staff_id'],
                                                'rank_id' => $payLoad['item']['rank_id'],
                                                'order_id' => $order->id,
                                                'status' => $order_status,
                                                'staff_choosed' => $payLoad['stuff_choosed'],
                                                'rank_schedule_id' => $payLoad['item']['rank_schedule_id'],
                                                'order_type' => $payLoad['order_type'],
                                                'is_deleted' => 0
                                            ]);
        $order_history = TblOrderHistory::where(['order_id'=>$order->id, 'is_deleted'=>0])->orderBy('created_at','desc')->first();

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

        //$ret['i'] = $order_serial_id.$ret['customer_first_name'];
        $ret['i'] =  '<div>【'.$order_history->order_type.'】</div><div>'.$order_serial_id.'</div><div>'.$ret['customer_first_name'].'</div><div>指名:'.$ret['staff_choosed'].'</div><div>'.$ret['menu_name'].'</div><br><div>カウセ</div><div>'.$ret['time'].'</div><div>'.$ret['staff_choosed'].'</div>' ;
        return $ret;
    }
}

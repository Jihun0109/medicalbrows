<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblOrder;
use DB;
use Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        Log::error($request->clinic_id);

        // $res = TblOrder::where([['is_deleted', 0],['clinic_id', $request->clinic_id]])->get(
        //     ['is_new', 'menu_id', 'start_time', 'end_time']
        // );
        
        // $res = TblOrder::raw('select staff_id, HOUR(start_time)-9 as start_idx from tbl_order)')
        //         ->where([['is_deleted', 0],['clinic_id', $request->clinic_id]])
        //         ->groupBy('staff_id')
        //         ->get(['staff_id', 'start_idx']);

        $res = TblOrder::select('staff_id', DB::raw('HOUR(start_time) as start_idx, HOUR(end_time) as end_idx'))
                    ->where([['is_deleted', 0],['clinic_id', $request->clinic_id]])
                    ->get();

        $idx_arr = [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19];
        $staff = [];
        $layout = [];   
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
                'i' => $res[$i]->staff_id,
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
                'i' => $res[$i]->staff_id,
                'static' =>  true
            ]);
            //rank_layout
            array_push($staff, (object)[
                'x' => ($i + 1)* 2,
                'y' => 1,
                'w' => 2,
                'h' => 1,
                'i' => $res[$i]->staff_id,
                'static' =>  true
            ]);
    
            $j = 0;
            while($j < sizeof($idx_arr)){   
                $target_state = false;
                $content = '';
                $real_h = 1;
                if ( $idx_arr[$j] >= $res[$i]->start_idx && $idx_arr[$j] < $res[$i]->end_idx){
                    $target_state = true;
                    $content = '再';
                    $real_h = ($res[$i]->end_idx - $res[$i]->start_idx);                                    
                }                    
                array_push($layout, (object)[
                    'x' => ($i + 1) * 2,
                    'y' => $j *3,
                    'w' => 2,
                    'h' => $real_h * 3,
                    'i' => $content,
                    'static' =>  $target_state
                ]);
                $j = $j + $real_h;   
            }
        }
        $ret = array('staff_layout' => $staff, 'content_layout'=> $layout);
        return $ret;
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
            'is_new' => 'required|numeric',
            'phone_number' => 'string|max:20',
            'order_id' => 'numeric',
            'staff_id' => 'required|numeric',
            'counselor_id' => 'required|numeric',
            'menu_id' => 'required|numeric',
            'clinic_id' => 'required|numeric',
            'description' => 'required|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            
            // 'email' => 'required|string|email|max:120|unique:tbl_users',
            // 'password' => 'required|string|min:8'
        ]);        
        return TblOrder::create([
            'is_new' => $request->is_new, 
            'phone_number' => $request->phone_number, 
            'order_id' => $request->order_id,
            'staff_id' => $request->staff_id,
            'counselor_id' => $request->counselor_id,
            'menu_id' => $request->menu_id,
            'clinic_id' => $request->clinic_id,
            'description' => $request->description,            
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
        Log::error($request);

        $this->validate($request, [
            'is_new' => 'required|numeric',
            'phone_number' => 'string|max:20',
            'order_id' => 'numeric',
            'staff_id' => 'required|numeric',
            'counselor_id' => 'required|numeric',
            'menu_id' => 'required|numeric',
            'clinic_id' => 'required|numeric',
            'description' => 'required|string',
            'phone_number' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);
        $order = TblOrder::findOrFail($id);
        $order->update($request->all());
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
        $order = TblOrder::findOrFail($id);
        
        $order->is_deleted = 1;
        $order->save();
        return ['message' => 'Order deleted'];
        
    }
}

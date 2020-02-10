<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblOrderHistory;
use DB;
use Log;

class OrderHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return TblOrderHistory::where('is_deleted', 0)->latest()->get();   
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
            'clinic_id'=> 'numeric',
            'staff_id'=> 'numeric',
            'rank_id'=> 'numeric',
            'order_id'=> 'numeric',
            'menu_id'=> 'numeric',
            'interviewer_id'=> 'numeric',
            'interviewer_start' => 'string', 
            'interviewer_end' => 'string', 
            'amount'=> 'numeric',
            'discount'=> 'numeric', 
            'status' => 'numeric',
            'order_route'=> 'string',         
            'staff_choosed'=> 'numeric',
        ]);       
        return TblOrder::create([
            'clinic_id'=> $request->clinic_id,
            'staff_id'=> $request->staff_id,
            'rank_id'=> $request->rank_id,
            'order_id'=> $request->order_id,
            'menu_id'=> $request->menu_id,
            'interviewer_id'=> $request->interviewer_id,
            'interviewer_start' => $request->interviewer_start,
            'interviewer_end' => $request->interviewer_end,
            'amount'=> $request->amount,
            'discount'=> $request->discount,
            'status' => $request->status,
            'order_route'=> $request->order_route,       
            'staff_choosed'=> $request->staff_choosed,
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
            'clinic_id'=> 'numeric',
            'staff_id'=> 'numeric',
            'rank_id'=> 'numeric',
            'order_id'=> 'numeric',
            'menu_id'=> 'numeric',
            'interviewer_id'=> 'numeric',
            'interviewer_start' => 'string', 
            'interviewer_end' => 'string', 
            'amount'=> 'numeric',
            'discount'=> 'numeric', 
            'status' => 'numeric',
            'order_route'=> 'string',         
            'staff_choosed'=> 'numeric',
        ]); 
        $orderhistory = TblOrderHistory::findOrFail($id);
        $orderhistory->update($request->all());
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
        $orderhistory = TblOrderHistory::findOrFail($id);
        
        $orderhistory->is_deleted = 1;
        $orderhistory->save();
        return ['message' => 'Order deleted'];
        
    }
}

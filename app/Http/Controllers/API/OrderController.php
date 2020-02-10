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
        return TblOrder::where('is_deleted', 0)->latest()->get();   
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
            'password' => 'string|min:8',
            'customer_id' => 'numeric',
            'customer_status' => 'numeric',
            'subtotal' => 'numeric',
            'discount' => 'numeric',
            'tax_id' => 'numeric',
            'total' => 'numeric',
            'note' => 'string',
            'order_date' => 'date',
            'cancel_date' => 'date',
        ]);        
        return TblOrder::create([
            'password' => $request->password, 
            'customer_id' => $request->customer_id, 
            'customer_status' => $request->customer_status,
            'subtotal' => $request->subtotal,
            'discount' => $request->discount,
            'tax_id' => $request->tax_id,
            'total' => $request->total,
            'note' => $request->note,            
            'order_date' => $request->order_date,
            'cancel_date' => $request->cancel_date,
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
            'password' => 'string|min:8',
            'customer_id' => 'numeric',
            'customer_status' => 'numeric',
            'subtotal' => 'numeric',
            'discount' => 'numeric',
            'tax_id' => 'numeric',
            'total' => 'numeric',
            'note' => 'string',
            'order_date' => 'date',
            'cancel_date' => 'date',
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

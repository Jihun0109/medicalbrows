<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblCustomer;
use DB;
use Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {        
        return TblCustomer::where('is_deleted', 0)->latest()->get();   
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
         // 'email' => 'required|string|email|max:120|unique:tbl_customer',
         // 'gender' => 'required|numeric',
            'first_name' => 'string|max:30',
            'last_name' => 'string|max:30',
            //'address' => 'string|max:250',
            'phonenumber' => 'string|max:30',
            'birthday' => 'required|date',                      
            
            // 'password' => 'required|string|min:8'
        ]); 
        return TblCustomer::create([
            'email' => $request->email,             
            'gender' => $request->gender,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber, 
            'birthday' => $request->birthday,            
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
        $this->validate($request, [
            // 'email' => 'required|string|email|max:120|unique:tbl_customer',
            // 'gender' => 'required|numeric',
               'first_name' => 'string|max:30',
               'last_name' => 'string|max:30',
               'address' => 'string|max:250',
               'phonenumber' => 'string|max:30',
               'birthday' => 'required|date',                      
               
               // 'password' => 'required|string|min:8'
        ]);  
        $customer = TblCustomer::findOrFail($id);
        $customer->update($request->all());
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
        $customer = TblCustomer::findOrFail($id);
        
        $customer->is_deleted = 1;
        $customer->save();
        return ['message' => 'Customer deleted'];
        
    }
}

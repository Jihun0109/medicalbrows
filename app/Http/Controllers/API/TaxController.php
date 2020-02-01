<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblTaxRate;
use Carbon\Carbon;
use Log;

class TaxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TblTaxRate::where('is_deleted', 0)->
                        latest()->
                        paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            // 'is_deleted' => 'required|numeric|max:1',
            // 'email' => 'required|string|email|max:120|unique:tbl_users',
            // 'password' => 'required|string|min:8'
        ]);

        $start_time = $request->start_time;
        $end_time = $request->end_time;

        return TblTaxRate::create([
            'name' => $request->name, 
            'amount'=>$request->amount, 
            'start_time'=>$start_time, 
            'end_time'=>$end_time,
            'is_deleted'=>0
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

            'name' => 'required|string|max:50',
            'amount' => 'required|numeric|min:0.01',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',

            // 'email' => 'required|string|email|max:120|unique:tbl_users,email,'.$user->id, //for updating for unique email
            // 'password' => 'required|string|min:8'
        ]);
        $item = TblTaxRate::findOrFail($id);
        $item->update($request->all());
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
        $item = TblTaxRate::findOrFail($id);
        
        $item->is_deleted = 1;
        $item->save();
        return ['message' => 'Tax deleted'];
        
    }
}

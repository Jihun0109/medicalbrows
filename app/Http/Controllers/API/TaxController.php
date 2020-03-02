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
        $keyword = \Request::get('keyword');
        if ($keyword){
            return TblTaxRate::where('is_deleted', 0)->
                              where(function($query) use ($keyword){
                                    $query->where('name','LIKE',"%".$keyword."%")->
                                            orWhere('start_time','LIKE BINARY',"%".$keyword."%")->
                                            orWhere('end_time','LIKE BINARY',"%".$keyword."%")->
                                            orWhere('id','LIKE',"%".$keyword."%");
                              })->latest()->get();
        }
        return TblTaxRate::where('is_deleted', 0)->
                        latest()->
                        get();
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
            'amount' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time', 
        ]);
      
        return TblTaxRate::create([
            'name' => $request->name, 
            'amount'=>$request->amount, 
            'start_time' => Carbon::parse($request->start_time),
            'end_time' => is_null($request->end_time)?null:Carbon::parse($request->end_time),
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
            'amount' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time', 
        ]);
        Log::info($request);
        $request['start_time'] = Carbon::parse($request->start_time);
        $request['end_time'] = is_null($request->end_time)?null:Carbon::parse($request->end_time);
        Log::info($request);
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

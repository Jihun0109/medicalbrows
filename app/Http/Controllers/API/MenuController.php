<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblMenu;
use Carbon\Carbon; 
use Illuminate\Validation\Rule;
use Log;
use DB;

class MenuController extends Controller
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
            return DB::table('tbl_menus')->
                            join('tbl_ranks','tbl_ranks.id','tbl_menus.rank_id')->
                            join('tbl_tax_rates','tbl_tax_rates.id','tbl_menus.tax_id')->
                            select('tbl_menus.*')->
                            where('tbl_menus.is_deleted',0)->
                            where(function($query) use ($keyword){
                                $query->where('tbl_menus.name','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_menus.code','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_menus.amount','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_ranks.name','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_tax_rates.name','LIKE',"%".$keyword."%");
                          })->latest()->paginate(100);
        }
        return TblMenu::where('is_deleted', 0)
                    ->latest()                    
                    ->paginate(100);
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
            'code' => 'required|string|max:20',
            'rank_id' => 'required',
            'tax_id' => 'required',
            'amount' => 'required|numeric|min:0|not_in:0',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',            
        ]);
        Log::info($request->start_time);
        return TblMenu::create([
            'name' => $request->name,
            'code' => $request->code,
            'rank_id' => $request->rank_id, 
            'tax_id' => $request->tax_id,
            'amount' => $request->amount,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'is_deleted' => 0,
            'is_vacation' => 0,
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
            'code' => 'required|string|max:20',
            'rank_id' => 'numeric',
            'tax_id' => 'numeric',
            'amount' => 'required|numeric|min:0|not_in:0',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time', 
        ]);
        $values = $request;
        $values['start_time'] = Carbon::parse($request->start_time);
        $values['end_time'] = is_null($request->end_time)?null:Carbon::parse($request->end_time);
        
        $item = TblMenu::findOrFail($id);
        $item->update($values->all());
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
        $item = TblMenu::findOrFail($id);
        
        $item->is_deleted = 1;
        $item->save();
        return ['message' => 'menu deleted'];
    }    
}

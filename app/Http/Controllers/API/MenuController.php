<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblMenu;
use Log;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TblMenu::where('is_deleted', 0)
                    ->latest()                    
                    ->paginate(20);
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
            'name' => 'required|string|max:50',
            'rank_id' => 'numeric',
            'tax_id' => 'numeric',
            'amount' => 'numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',            
        ]);
        return TblMenu::create([
            'name' => $request->name, 
            'rank_id' => $request->rank_id, 
            'tax_id' => $request->tax_id,
            'amount' => $request->amount,
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
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'rank_id' => 'numeric',
            'tax_id' => 'numeric',
            'amount' => 'numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',            
        ]);      
        $item = TblMenu::findOrFail($id);
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
        $item = TblMenu::findOrFail($id);
        
        $item->is_deleted = 1;
        $item->save();
        return ['message' => 'menu deleted'];
    }    
}

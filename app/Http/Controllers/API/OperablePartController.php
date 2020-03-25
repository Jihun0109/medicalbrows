<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblOperablePart;
class OperablePartController extends Controller
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
            return TblOperablePart::where('is_deleted', 0)->
                                    where(function($query) use ($keyword){
                                    $query->where('name','LIKE',"%".$keyword."%")->
                                            orWhere('id','LIKE',"%".$keyword."%");
                              })->get();
        }

        return TblOperablePart::where('is_deleted', 0)->get();
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
            // 'email' => 'required|string|email|max:120|unique:tbl_users',
            // 'password' => 'required|string|min:8'
        ]);
        return TblOperablePart::create(['name' => $request->name, 'is_deleted' => 0]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
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
            // 'email' => 'required|string|email|max:120|unique:tbl_users,email,'.$user->id, //for updating for unique email
            // 'password' => 'required|string|min:8'
        ]);
        $part = TblOperablePart::findOrFail($id);
        $part->update($request->all());
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
        $part = TblOperablePart::findOrFail($id);
        
        $part->is_deleted = 1;
        $part->save();
        return ['message' => 'OperablePart deleted'];
    }
}

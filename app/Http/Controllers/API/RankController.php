<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblRank;

class RankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TblRank::where('is_deleted', 0)->get();
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
            'short_name' => 'required|string|max:5',
            // 'email' => 'required|string|email|max:120|unique:tbl_users',
            // 'password' => 'required|string|min:8'
        ]);
        return TblRank::create(['name' => $request->name, 'short_name' => $request->short_name]);
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
            'short_name' => 'required|string|max:5',
            // 'email' => 'required|string|email|max:120|unique:tbl_users,email,'.$user->id, //for updating for unique email
            // 'password' => 'required|string|min:8'
        ]);
        $rank = TblRank::findOrFail($id);
        $rank->update($request->all());
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
        $rank = TblRank::findOrFail($id);
        
        $rank->is_deleted = 1;
        $rank->save();
        return ['message' => 'Rank deleted'];
        
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Facades\Voyager;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Voyager::model('User')
                    ->where('is_deleted', 0)
                    ->latest()                    
                    ->paginate(10);
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
            'user_id' => 'required|string|max:50|unique:users',
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:120|unique:users',
            'password' => 'required|string|min:8',
        ]);
        return Voyager::model('User')->create([
                            'user_id' => $request->user_id,
                            'name' => $request->name,
                            'email' => $request->email,
                            'password' => Hash::make($request->password),
                            'role_id' => $request->role_id,
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
            'user_id' => 'required|string|max:50|unique:users,user_id,'.$id,
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:120|unique:users,email,'.$id,
            //'password' => 'string|min:8',
        ]);
        $rank = Voyager::model('User')->findOrFail($id);
        if ($request->password)
            $rank->update(['user_id'=>$request->user_id, 'name'=>$request->name, 'email'=>$request->email,'role_id'=>$request->role_id, 'password'=>Hash::make($request->password)]);
        else
            $rank->update(['user_id'=>$request->user_id, 'name'=>$request->name, 'email'=>$request->email,'role_id'=>$request->role_id]);
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
        $rank = Voyager::model('User')->findOrFail($id);
        
        $rank->is_deleted = 1;
        $rank->save();
        return ['message' => 'Deleted'];
    }
}
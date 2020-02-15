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
        $keyword = \Request::get('keyword');
        if ($keyword){
            return DB::table('users')->
                            join('roles','roles.id','users.role_id')->
                            select('users.*')->
                            where('users.is_deleted',0)->
                            where(function($query) use ($keyword){
                                $query->where('users.name','LIKE',"%".$keyword."%")->
                                        orWhere('users.email','LIKE',"%".$keyword."%")->
                                        orWhere('users.user_id','LIKE',"%".$keyword."%")->
                                        orWhere('roles.display_name','LIKE',"%".$keyword."%");
                          })->latest()->get();
        }
        return Voyager::model('User')
                    ->where('is_deleted', 0)
                    ->latest()
                    ->get();
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
            $rank->update(['user_id'=>$request->user_id, 'name'=>$request->name, 'email'=>$request->email,'role_id'=>$request->role_id, 'is_active'=>$request->is_active, 'password'=>Hash::make($request->password)]);
        else
            $rank->update(['user_id'=>$request->user_id, 'name'=>$request->name, 'email'=>$request->email,'role_id'=>$request->role_id, 'is_active'=>$request->is_active,]);
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

    public function getClinicIdsWithEmail(Request $request)
    {
        $clinic_role_id = DB::table('roles')->where('name','clinic')->value('id');
        $registered_emails = DB::table('tbl_clinics')->where('is_deleted',0)->select('email')->pluck('email');
        return DB::table('users')->                            
                            where('is_deleted',0)->
                            where('role_id', $clinic_role_id)->
                            whereNotIn('email', $registered_emails)->
                            select('email')->
                            pluck('email');
    }
}

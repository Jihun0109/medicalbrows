<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblClinic;
use Auth;
use Log;
use DB;

class ClinicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $user = Auth::user();
        // return $user;
        $keyword = \Request::get('keyword');
        $clinic_email = \Request::get('email');

        if ($keyword){
            return DB::table('tbl_clinics')->
                                join('users','users.email','tbl_clinics.email')->
                                where('tbl_clinics.is_deleted',0)->
                                where(function($query) use ($keyword){
                                    $query->where('tbl_clinics.name','LIKE',"%".$keyword."%")->
                                            orWhere('tbl_clinics.id','LIKE',"%".$keyword."%")->
                                            orWhere('tbl_clinics.email','LIKE',"%".$keyword."%")->
                                            orWhere('tbl_clinics.address','LIKE',"%".$keyword."%");
                              })->where('tbl_clinics.is_vacation', 0)->
                                select('tbl_clinics.*','users.user_id')->get();
        } else if ($clinic_email){
            return TblClinic::where('is_deleted', 0)->                            
                            where('email',$clinic_email)->get();
        }

        return DB::table('tbl_clinics')->
                        join('users','users.email','tbl_clinics.email')->
                        where(['tbl_clinics.is_deleted'=>0, 'tbl_clinics.is_vacation' => 0])->
                        select('tbl_clinics.*', 'users.user_id')->get();
                        
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
            'address' => 'required|string|max:50',
            'name' => 'required|string|max:50',
            'user_id' => 'required|string|email|max:120',
            'is_vacation' => 'required|numeric|max:10',            
        ]);
        return TblClinic::create([
                            'name' => $request->name,
                            'email' => $request->email,
                            'address' => $request->address,
                            'is_vacation' => $request->is_vacation,
                            'is_deleted' => 0
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
        Log::info($request);
        Log::info($id);
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:120',//|unique:tbl_clinics,email,'.$id,
            'address' => 'string|max:50',
        ]);
        $clinic = TblClinic::findOrFail($id);
        $clinic->update($request->all());
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
        $clinic = TblClinic::findOrFail($id);
        
        $clinic->is_deleted = 1;
        $clinic->save();
        return ['message' => 'Clinic deleted'];
    }
}

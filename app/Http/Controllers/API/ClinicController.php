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
        $keyword = \Request::get('keyword')??"";
        $clinic_user_id = \Request::get('user_id');
        $isActive = \Request::get('isActive');
        $vacations = [0,1];
        if ($isActive){
            $vacations = [0];
        }
        if ($clinic_user_id){
            return DB::table('tbl_relation_clinic_user')
                            ->join('tbl_clinics','tbl_clinics.id', 'tbl_relation_clinic_user.clinic_id')
                            ->where(['tbl_relation_clinic_user.user_id' => $clinic_user_id, 'tbl_clinics.is_deleted'=>0])
                            ->select('tbl_clinics.*')->get();
        }
    
        $clinics  = DB::table('tbl_clinics')->
                            //join('users','users.email','tbl_clinics.email')->
                            where('tbl_clinics.is_deleted',0)->
                            whereIn('tbl_clinics.is_vacation', $vacations)->
                            where(function($query) use ($keyword){
                                $query->where('tbl_clinics.name','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_clinics.id','LIKE',"%".$keyword."%")->
                                        // orWhere('tbl_clinics.email','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_clinics.address','LIKE',"%".$keyword."%");
                            //})->select('tbl_clinics.*','users.user_id')->get();
                            })->select('tbl_clinics.*')->get()
                            ->map(function ($item){
                                $item->users = [];
                                $clinic_users = DB::table('tbl_relation_clinic_user')
                                                ->join('users','users.id','tbl_relation_clinic_user.user_id')
                                                ->where('tbl_relation_clinic_user.clinic_id',$item->id)
                                                ->select('tbl_relation_clinic_user.user_id as id','users.user_id','users.email')->get();
                                $emails = [];
                                for ($j=0; $j<sizeof($clinic_users); $j++){
                                    array_push($item->users, array('key'=>$clinic_users[$j]->id, 'value'=>$clinic_users[$j]->user_id));
                                    array_push($emails, $clinic_users[$j]->email);
                                }

                                $item->email = $emails;

                                return $item;
                            });

        return $clinics;        
                        
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
            //'user_id' => 'required|string|max:120',            
            'is_vacation' => 'required|numeric|max:10',            
        ]);
        
        $clinic = TblClinic::create([
                            'name' => $request->name,
                            //'email' => $request->email,
                            'address' => $request->address,
                            'is_vacation' => $request->is_vacation,
                            'is_deleted' => 0
                            ]);

        $clinic_users = [];
        for ($i=0; $i<sizeof($request->users); $i++){
            array_push($clinic_users, array('clinic_id'=>$clinic->id, 'user_id'=>$request->users[$i]['key']));            
        }

        if (sizeof($clinic_users)){
            //DB::table('tbl_relation_clinic_user')->where('clinic_id', $clinic->id);
            DB::table('tbl_relation_clinic_user')->insert($clinic_users);
        }
        return 1;
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
           // 'email' => 'required|string|email|max:120',//|unique:tbl_clinics,email,'.$id,
            'users' => 'required',
            'address' => 'string|max:50',
        ]);
        $clinic = TblClinic::findOrFail($id);
        $clinic->update(['name'=>$request->name, 'address'=>$request->address, 'is_vacation'=>$request->is_vacation, 'password'=>$request->password]);
        $clinic_users = [];
        for ($i=0; $i<sizeof($request->users); $i++){
            array_push($clinic_users, array('clinic_id'=>$clinic->id, 'user_id'=>$request->users[$i]['key']));            
        }

        if (sizeof($clinic_users)){
            DB::table('tbl_relation_clinic_user')->where('clinic_id', $clinic->id)->delete();
            DB::table('tbl_relation_clinic_user')->insert($clinic_users);
        }
        
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

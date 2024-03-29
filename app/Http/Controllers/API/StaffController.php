<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaff;
use DB;
use Log;

class StaffController extends Controller
{
    public function operators()
    {
        return TblStaff::where([['is_deleted', 0],['staff_type_id', '<>', 7]])->get();
    }

    public function counselors()
    {
        return TblStaff::where([['is_deleted', 0],['staff_type_id', '=', 7]])->get();
                    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword = \Request::get('keyword');
        if ($keyword){
            return DB::table('tbl_staffs')->
                            join('tbl_clinics','tbl_clinics.id','tbl_staffs.clinic_id')->
                            join('tbl_staff_types','tbl_staff_types.id','tbl_staffs.staff_type_id')->
                            select('tbl_staffs.*')->
                            where('tbl_staffs.is_deleted',0)->
                            where(function($query) use ($keyword){
                                $query->where('tbl_staffs.full_name','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_staffs.id','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_staffs.alias','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_clinics.name','LIKE',"%".$keyword."%")->
                                        orWhere('tbl_staff_types.name','LIKE',"%".$keyword."%");
                          })->latest()->get();
        }
        return TblStaff::where('is_deleted', 0)->get();
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
            'full_name' => 'required|string|max:100',
            'alias' => 'required|string|max:100|unique:tbl_staffs',
            'staff_type_id' => 'required',
            'clinic_id' => 'required',
            'is_vacation' => 'required',
        ]);
        return TblStaff::create([
                            'full_name' => $request->full_name,
                            'alias' => $request->alias,
                            'staff_type_id' => $request->staff_type_id,
                            'clinic_id' => $request->clinic_id,
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
        $this->validate($request, [
            'full_name' => 'required|string|max:100',
            'alias' => 'required|string|max:100|unique:tbl_staffs,alias,'.$id,
            'staff_type_id' => 'required',
            'clinic_id' => 'required',
            'is_vacation' => 'required',
            //'email' => 'required|string|email|max:120|unique:tbl_clinics',
        ]);        
        $staff = TblStaff::findOrFail($id);
        $staff->update($request->all());
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
        $staff = TblStaff::findOrFail($id);
        
        $staff->is_deleted = 1;
        $staff->save();
        return ['message' => 'staff deleted'];
    }    
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblClinic;

class ClinicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TblClinic::where('is_deleted', 0)->latest()->get();
                    
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
            'email' => 'required|string|email|max:120|unique:tbl_clinics',
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
        $this->validate($request, [
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:120|unique:tbl_clinics,email,'.$id,
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

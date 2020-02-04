<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblMenu;

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
        $this->validate($request, [
            'name' => 'required|string|max:50',
            //'email' => 'required|string|email|max:120|unique:tbl_clinics',
        ]);
        return TblMenu::create([
                            'name' => $request->name,
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
            //'email' => 'required|string|email|max:120|unique:tbl_clinics',
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

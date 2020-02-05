<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaffRank;
use Carbon\Carbon;


use Illuminate\Validation\Rule;



class StaffRankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TblStaffRank::where('is_deleted', 0)->latest()->get();
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
            'rank_id' => 'required',
            'staff_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query){
                                return $query->where('is_deleted','0');
                            })
                        ],
        ]);
        
        return TblStaffRank::create([
            'rank_id' => $request->rank_id,
            'staff_id' => $request->staff_id,
            'promo_date' => Carbon::parse($request->promo_date)->format('Y-m-d'),
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
            'rank_id' => 'required',
            'staff_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query) use ($request) {
                                return $query->where('is_deleted','0')->where('id',"<>", $request->id);
                            })
                        ],
        ]);

        $staffrank = TblStaffRank::findOrFail($id);        
        $staffrank->update($request->all());
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
        $staffrank = TblStaffRank::findOrFail($id);
        
        $staffrank->is_deleted = 1;
        $staffrank->save();
        return ['message' => 'staffrank deleted'];
    }
}

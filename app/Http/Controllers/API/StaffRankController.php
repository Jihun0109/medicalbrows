<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TblStaffRank;
use App\TblStaffRankHistory;
use Carbon\Carbon;
use DB;
use Log;
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
        $keyword = \Request::get('keyword')??'';
        
            $maindata = DB::table('tbl_staff_ranks')->                
                join('tbl_staffs','tbl_staffs.id','tbl_staff_ranks.staff_id')->
                join('tbl_ranks','tbl_ranks.id','tbl_staff_ranks.rank_id')->
                select('tbl_staff_ranks.*')->
                where('tbl_staff_ranks.is_deleted', 0)->
                where(function($query) use ($keyword){
                    $query->where('tbl_staffs.full_name','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_ranks.unique_id','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_ranks.promo_date','LIKE BINARY',"%".$keyword."%")->
                            orWhere('tbl_staffs.alias','LIKE',"%".$keyword."%")->
                            orWhere('tbl_ranks.name','LIKE',"%".$keyword."%");
              })->latest()->get();

            Log::info($maindata);
            $historydata = DB::table('tbl_staff_rank_histories')->                
                join('tbl_staffs','tbl_staffs.id','tbl_staff_rank_histories.staff_id')->
                join('tbl_ranks','tbl_ranks.id','tbl_staff_rank_histories.rank_id')->
                select('tbl_staff_rank_histories.*')->
                where('tbl_staff_rank_histories.is_deleted', 0)->
                where(function($query) use ($keyword){
                    $query->where('tbl_staffs.full_name','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_rank_histories.unique_id','LIKE',"%".$keyword."%")->
                            orWhere('tbl_staff_rank_histories.promo_date','LIKE BINARY',"%".$keyword."%")->
                            orWhere('tbl_staffs.alias','LIKE',"%".$keyword."%")->
                            orWhere('tbl_ranks.name','LIKE',"%".$keyword."%");
              })->latest()->get();
              
              return $maindata->merge($historydata)->map(function($item){
                    $item->parts = [];
                    $staff_parts = DB::table('tbl_relation_staff_part')
                                    ->join('tbl_operable_parts','tbl_operable_parts.id','tbl_relation_staff_part.part_id')
                                    ->where('tbl_relation_staff_part.staffrank_id',$item->unique_id)
                                    ->select('tbl_operable_parts.id','tbl_operable_parts.name')->get();                    
                    for ($j=0; $j<sizeof($staff_parts); $j++){
                        array_push($item->parts, array('key'=>$staff_parts[$j]->id, 'value'=>$staff_parts[$j]->name));                        
                    }
                    return $item;
              });

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $rows = TblStaffRank::all();
        // for ($i=0; $i<sizeof($rows); $i++){
        //     $row = $rows[$i];
        //     $row->unique_id = $row->id;
        //     $row->save();
        // }
        $staff_id = $request->staff_id;
        $rank_id = $request->rank_id;
        //Log::info($request);
        $this->validate($request, [
            'rank_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query) use ($staff_id, $rank_id){
                                return $query->where('staff_id', $staff_id)->where('rank_id',$rank_id);
                            })],
            'staff_id' => ['required',
                            Rule::unique('tbl_staff_ranks')->where(function ($query) use ($staff_id, $rank_id){
                                return $query->where('staff_id', $staff_id)->where('rank_id',$rank_id);
                            })],
            //'parts' => 'required',
        ]);

        
        $main = TblStaffRank::max('unique_id')??0;
        $history = TblStaffRankHistory::max('unique_id')??0;
        $unique = max($main, $history) + 1;

        $old_row = TblStaffRank::where('staff_id',$staff_id)->first();
        
        if ($old_row){
            TblStaffRankHistory::create([
                'rank_id' => $old_row->rank_id,
                'staff_id' => $old_row->staff_id,
                'promo_date' => $old_row->promo_date,
                'unique_id' => $old_row->unique_id
            ]);

            $values = $request;
            $values['unique_id'] = $unique;
            $values['promo_date'] = Carbon::parse($request->promo_date);
            Log::info($values->all());
            $old_row->update($values->all());
            
        } else {            
            TblStaffRank::create([
                'rank_id' => $request->rank_id,
                'staff_id' => $request->staff_id,
                'promo_date' => Carbon::parse($request->promo_date)->format('Y-m-d'),
                'unique_id' => $unique
            ]);
        }

        // 한 스타프가 여러개의 Operable_part를 가질수 있다. 태그형식 ({key1: value1, key2: value2})형식의 자료로 올라온다.
        $staff_parts = [];
        for ($i=0; $i<sizeof($request->parts); $i++){
            array_push($staff_parts, array('staffrank_id'=>$unique, 'part_id'=>$request->parts[$i]['key']));            
        }

        if (sizeof($staff_parts)){            
            DB::table('tbl_relation_staff_part')->insert($staff_parts);
        }
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
    public function update(Request $request, $unique_id)
    {
        //Log::info($request);
        $this->validate($request, [
            'rank_id' => 'required',
            // 'staff_id' => ['required',
            //                 Rule::unique('tbl_staff_ranks')->where(function ($query) use ($request) {
            //                     return $query->where('is_deleted','0')->where('id',"<>", $request->id);
            //                 })
            //             ],
            // 'parts' => 'required',
        ]);
        $values = $request;        
        $request['promo_date'] = Carbon::parse($request->promo_date);        
        $staffrank = TblStaffRank::where('unique_id', $unique_id)->first()??TblStaffRankHistory::where('unique_id',$unique_id)->first();
        $staffrank->update($values->all());

        $staff_parts = [];
        for ($i=0; $i<sizeof($request->parts); $i++){
            array_push($staff_parts, array('staffrank_id'=>$unique_id, 'part_id'=>$request->parts[$i]['key']));            
        }

        if (sizeof($staff_parts)){
            DB::table('tbl_relation_staff_part')->where('staffrank_id', $unique_id)->delete();          
            DB::table('tbl_relation_staff_part')->insert($staff_parts);
        }

        return $unique_id;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($unique_id)
    {
        $staffrank = TblStaffRank::where('unique_id', $unique_id)->first()??TblStaffRankHistory::where('unique_id',$unique_id);
        
        $staffrank->is_deleted = 1;
        $staffrank->save();
        return ['message' => 'staffrank deleted'];
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Activity;
use Illuminate\Support\Facades\Validator;
use Auth;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $activities = Activity::all();
        return response()->json(['stat'=>'success','msg'=>'fetche all activities','data'=>$activities],200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $userdata = Auth::user();
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required'

        ]);
        if($validator->fails()){
            return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
        }else{
            $activity = new Activity();
            $activity->name=$data['name'];
            $activity->user_id=$userdata->id;
            $activity->save();
        }


     return response()->json(['stat'=>'success','msg'=>'Activity added successfully','data'=>$activity],201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request)
    {
        try{
            $userdata = Auth::user();
            $data = $request->all();
            $validator = Validator::make($data, [
                'name' => 'required',
                'id'=>'required'
            ]);
          if($validator->fails()){
            return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
          } else{
              $activity = Activity::find($data['id']);
              $activity->name=$data['name'];
              $activity->user_id = $userdata->id;
              $activity->save();
              return response()->json(['stat'=>'success','msg'=>'update active successfully','data'=>$activity],200);
          }

        }catch(\Exception $ex){
           return response()->json(['stat'=>"error",'msg'=>'somthing wrong','error'=>$ex]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try{
            $userdata = Auth::user();
            $data = $request->all();
            $validator = Validator::make($data, [

                'id'=>'required'
            ]);
          if($validator->fails()){
            return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
          } else{
              $activity = Activity::find($data['id']);
              $activity->delete();
              return response()->json(['stat'=>'success','msg'=>'delete active successfully','data'=>$activity],200);
          }

        }catch(\Exception $ex){
           return response()->json(['stat'=>"error",'msg'=>'somthing wrong','error'=>$ex]);
        }
    }
}

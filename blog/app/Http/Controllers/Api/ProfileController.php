<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use File;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userdata = Auth::user();
            $data = User::find($userdata->id);
            $user_info = [
                'id' => $data->id,
                "email" => $data->email,
                "image" => asset('public/uploads/avator/' . $data->image),
                "name" => $data->name,
                "gender" => $data->gender,
                "phone" => $data->contact
            ];
            return response()->json(['stat' => 'success', 'data' => $user_info], 200);
        } catch (\Exception $ex) {
            return response()->json(['stat' => 'success', 'msg' => "somthing wrong with this api"], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateprofilepic(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
        } else {

            if ($request->hasFile('image')) {

                $image = $request->file('image');

                $data['images'] = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/avator/');
                $image->move($destinationPath, $data['images']);
            }


            if (!empty($data['images'])) {
                $user = User::find($request->user_id);
                $filename = public_path('uploads/avator/' . $user->image);
                File::delete($filename);
                $user->image = $data['images'];
                $user->save();
            }
            $user_info =  [
                'id'=>$user->id,
                'image'=>asset('public/uploads/avator/'.$user->image)
            ];
            return response()->json(['stat' => 'success', 'msg' => "profile picture updated successfully", 'data' => $user_info]);
        }
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

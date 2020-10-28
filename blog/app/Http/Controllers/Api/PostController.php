<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Auth;
use File;


use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userdata = Auth::user();
        $data = $request->all();
        $validator = Validator::make($data, [
            'title' => 'required',
            'description' => 'required',
           // 'user_id'=>'required',
            'activity_id'=>'required',
            'location'=>'required',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'status'=>'required',
            'type'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
        }
        else{
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $data['image'] = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('uploads/post/');
                $image->move($destinationPath, $data['image']);
            }
            $post = new Post();
            $post->title=$data['title'];
            $post->description=$data['description'];
            $post->user_id=$userdata->id;
            $post->activity_id=$data['activity_id'];
            $post->location=$data['location'];
            $post->image=$data['image'];
            $post->status=$data['status'];
            $post->save();
        }
        return response()->json(['stat'=>'success','msg'=>'Post save successfully','data'=>$post],200);
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
       try{
            $post = Post::where('id','=',$id)->with(["activity"])->get();
           return response()->json(["stat"=>"success","msg"=>"edit details fetch Successfully",'data'=>$post],200);
        }
        catch(\Exception $ex){
            return response()->json(['stat'=>'error','msg'=>'id not found'],404);
        }

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
        // $userdata = Auth::user();
        // $data = $request->all();
        // $validator = Validator::make($data, [
        //     'title' => 'required',
        //     'description' => 'required',
        //    // 'user_id'=>'required',
        //     'activity_id'=>'required',
        //     'location'=>'required',
        //     'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        //     'status'=>'required',
        //     'type'=>'required'
        // ]);
        // if ($validator->fails()) {
        //     return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
        // }
        // else{
        //     if ($request->hasFile('image')) {
        //         $image = $request->file('image');
        //         $data['image'] = time() . '.' . $image->getClientOriginalExtension();
        //         $destinationPath = public_path('uploads/post/');
        //         $image->move($destinationPath, $data['image']);
        //     }
        //     $post = new Post();
        //     $post->title=$data['title'];
        //     $post->description=$data['description'];
        //     $post->user_id=$userdata->id;
        //     $post->activity_id=$data['activity_id'];
        //     $post->location=$data['location'];
        //     $post->image=$data['image'];
        //     $post->status=$data['status'];
        //     $post->save();
        // }
        // return response()->json(['stat'=>'success','msg'=>'Post save successfully','data'=>$post],200);
        echo "hello"; die;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $post = Post::where('id','=',$id)->first();
            if($post->count()){
                $filename =public_path('uploads/post/'.$post->image);
                File::delete($filename);
                $post->delete();


                return response()->json(["stat"=>"success","msg"=>"post deleted successfully"],200);
            }else{

                return response()->json(['stat'=>'error','msg'=>'id not found'],404);
            }
        }
        catch(\Exception $ex){
          return response()->json(['stat'=>"success","msg"=>"somthing wrong with this api"],400);
        }

    }
}

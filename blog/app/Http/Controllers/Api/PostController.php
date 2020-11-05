<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use Auth;
use File;
use App\Comment;

use Illuminate\Support\Facades\Validator;
class PostController extends Controller
{
  public function index(){
    $post = Post::with(['activity','user','comment'])->get();
    return response()->json(['stat'=>'success','msg'=>"post fetch successfully","data"=>$post],200);
  }
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
        $userdata = Auth::user();
         $data = $request->all();

        $validator = Validator::make($data, [
            'title' => 'required',
            'description' => 'required',
            'id'=>'required',

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
            $post = Post::find($data['id']);
            $filename =public_path('uploads/post/'.$post->image);
            File::delete($filename);
           if($post->count() > 0){
            $post->title=$data['title'];
            $post->description=$data['description'];
            $post->user_id=$userdata->id;
            $post->activity_id=$data['activity_id'];
            $post->location=$data['location'];
            if(!empty($request->image)){
                $post->image=$data['image'];
            }

            $post->status=$data['status'];
            $post->save();

           }

        }
        return response()->json(['stat'=>'success','msg'=>'Post save successfully','data'=>$post],200);

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
    public function create_comment(Request $request){

        $userdata = Auth::user();
        $data = $request->all();
       $validator = Validator::make($data, [
          "post_id"=>"required",
         // "parent_id"=>"required",
          "comment"=>"required",
          //"status"=>"required"

       ]);
       if ($validator->fails()) {
               return response()->json(['stat' => "errors", "errors" => $validator->errors()]);
        }else{
               $comment  = new Comment();
               $comment->user_id = $userdata->id;
               $comment->post_id = $request->post_id;
               $comment->parent_id = (!empty($request->parent_id))?$request->parent_id:0;
               $comment->comment = $request->comment;
               $comment->status = 'A';
               $comment->save();
               return response()->json(['stat'=>'success','msg'=>"comment added successfully","data"=>$comment],200);
    }
    }
    public function get_comments(Request $request){
        $userdata = Auth::user();
        $data = $request->all();
       $validator = Validator::make($data, [
          "post_id"=>"required",
         // "parent_id"=>"required",

          //"status"=>"required"

       ]);
    }
}

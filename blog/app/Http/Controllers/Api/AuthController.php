<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->all();
   try{
    $validator = Validator::make($data, [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'image' => 'image|mimes:jpeg,png,jpg|max:2048',
        'gender'=>'required',
        'contact'=>'required'

    ]);
    if($validator->fails()){
        return response()->json(['stat'=>"error","errors"=>$validator->errors()],400);
    }
    else{
        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $data['images'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/avator/');
            $image->move($destinationPath,$data['images']);
         }

        $user=User::create([
            "name"=>$data['name'],
            "email"=>$data['email'],
            'password'=>Hash::make($data['password']),
            'gender'=>$data['gender'],
            'contact'=>$data['contact'],
            'image'=>$data['images']
            ]);
       return response()->json(["stat"=>"success","msg"=>"user has been registered successfully","data"=>$user]);
    }
   }
   catch(\Exception $ex){
    return response()->json(['stat'=>"error","msg"=>"somthing wrong with this Api"],400);
   }


    }
    public function login(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['stat'=>"error","errors"=>$validator->errors()],400);
        }
        else{
            $user = User::where('email',$data['email'])->first();
            if($user){
                if(Hash::check($data['password'],$user->password))
                {
                   return response()->json(['stat'=>'success','access-token'=>'Bearer'.' '.$user->createToken('My App')->accessToken],200);
                }
                else{
                    return response()->json(['stat'=>'error','msg'=>'Password not matched'],400);
                }

            }
            else{
                return response()->json(['stat'=>'error','msg'=>"user not found"],400);
            }
        }
    }

    public function logoutApi()
    {
        if(Auth::check()) {
        Auth::user()->AauthAcessToken()->delete();
        return response()->json(['stat'=>'success','msg'=>'User is successfully logout'],200);
        }
    }
    public function changePassword(Request $request){
        $data = $request->all();
        $userdata = Auth::user();
        $validator = Validator::make($data, [
            'current_password' =>'required|string',
            'new_password' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['stat'=>'error','errors'=>$validator->errors()],400);

        }else{
            if(Hash::check($data['current_password'],$userdata->password)){
                User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
                return response()->json(['stat'=>'success','msg'=>'password is changed'],200);
            }else{
                return response()->json(['stat'=>'error','msg'=>'current password is not matched']);
            }




        }

    }
}

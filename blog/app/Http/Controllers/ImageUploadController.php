<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cloudder;
use App\Upload;
use App\Multiple;
class ImageUploadController extends Controller
{
    //
    public function home()
   {
    $images = Upload::where('image_url','!=',null)->get();
       return view('home',compact('images'));
   }
   public function uploadImages(Request $request)
   {
    $this->validate($request,[
        'image_name'=>'required|mimes:jpeg,bmp,jpg,png|between:1, 6000',
    ]);
    $image = $request->file('image_name');
    $name = $request->file('image_name')->getClientOriginalName();
    $image_name = $request->file('image_name')->getRealPath();
    Cloudder::upload($image_name, null);
    list($width, $height) = getimagesize($image_name);
    $image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
    $this->saveImages($request, $image_url);

    return redirect()->back()->with('status', 'Image Uploaded Successfully');

   }
   public function saveImages(Request $request, $image_url)
   {
       $image = new Upload();
       $image->image_name = $request->file('image_name')->getClientOriginalName();
       $image->image_url = $image_url;
       $image->save();
   }
   public function deleteimage($id){
    $imag_info = Upload::find($id);
    $url= explode("/",@$imag_info->image_url);
    $get_name =explode('.',$url[(count($url)-1)]);
    $public_id = $get_name[0];
    Cloudder::delete($public_id,null);
    $imag_info->delete();
    return redirect()->back()->with('status', 'Image deleted Successfully');

   }
   public function videoupload(){
    $videos = Upload::where('video_url','!=',null)->get();
    return view('homevideo',compact('videos'));
   }
   public function savevideo(Request $request){
    $this->validate($request,[
        'file_name'=>'required',
    ]);
      $image = $request->file('file_name');

    $name = $request->file('file_name')->getClientOriginalName();

    $image_name = $request->file('file_name')->getRealPath();

     $data= Cloudder::uploadVideo($image_name, null);
     $cloundary_upload = Cloudder::getResult();
     $Uploaded_url =$cloundary_upload['url'];
     $video = new Upload();
     $video->video_name = $image_name;
     $video->video_url=$Uploaded_url;
     $video->save();

     return redirect()->back()->with('status', 'Video Uploaded Successfully');
   }
   public function deletevideo($id)
   {
      $upload = Upload::findOrfail($id);
      $url= explode("/",@$upload->video_url);
      $get_name =explode('.',$url[(count($url)-1)]);
      $public_id = $get_name[0];
      Cloudder::delete($public_id,$options = [ 'resource_type' => 'video' ]);
      $upload->delete();
      return redirect()->back()->with('status', 'Video Deleted Successfully');
    
   }
   public function uploadmultipleImages()
   {
      $multple= Multiple::all();
     
      return view('multipleimage',compact('multple'));
   }
   public function submitmultiple(Request $request)
   {
    $this->validate($request, [
    'filename' => 'required',
    'filename.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

     if($request->hasfile('filename'))
         {

            foreach($request->file('filename') as $image)
            {

              // $image = $request->file('file_name');

               $name = $image->getClientOriginalName();
               $image_name = $image->getRealPath();
               Cloudder::upload($image_name, null);
               list($width, $height) = getimagesize($image_name);
               $image_url= Cloudder::show(Cloudder::getPublicId(), ["width" => $width, "height"=>$height]);
                //$name=$image->getClientOriginalName();
               // $image->move(public_path().'/images/', $name);  
                  //$data[] = $name;  
                  $form= new Multiple();
                  $form->image_url= $image_url;
                  $form->save();
            }
         }

       

        return back()->with('success', 'Your images has been successfully');

   }
}

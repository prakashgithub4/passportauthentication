<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable =['title','description','user_id','activity_id','location','image','type','status'];
    public $timestamp;


  function activity(){
        return $this->belongsTo('App\Activity');
    }

}

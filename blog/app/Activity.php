<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{

    public function post(){

        return $this->hasOne('App\Activity','activity_id','id');
     }

}

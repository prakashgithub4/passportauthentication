<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Multiple extends Model
{
    //
    protected $fillable =['image_url'];
    protected $table = 'multiple_image';
    public $timestamp;
}

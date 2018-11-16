<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['network_api_id','name','city','country','lat','long','api_source'];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CrawledNode extends Model
{
    protected $fillable = ['ip','continent_code','country_code','country_name','region_code','region_name','city','zip','latitude','longitude'];
}

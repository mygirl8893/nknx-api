<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['code','parameter'];

    public function header()
    {
    	return $this->belongsTo('App\Header');
    }
}

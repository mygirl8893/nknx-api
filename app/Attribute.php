<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['data','usage'];
    public function transaction()
    {
    	return $this->belongsTo('App\Transaction');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payload extends Model
{
    protected $fillable = ['sigChain','submitter'];
    public function transaction()
    {
    	return $this->belongsTo('App\Transaction');
    }
}

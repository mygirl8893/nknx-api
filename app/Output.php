<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Output extends Model
{
    protected $fillable = ['address','assetID','value'];

    public function transaction()
    {
    	return $this->belongsTo('App\Transaction');
    }
}

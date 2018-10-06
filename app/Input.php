<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    protected $fillable = ['referTxID','referTxOutputIndex'];
    public function transaction()
    {
    	return $this->belongsTo('App\Transaction');
    }
}

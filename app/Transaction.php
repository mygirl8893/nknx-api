<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['hash','payloadVersion','txType'];

    public function block()
    {
    	return $this->belongsTo('App\Block');
    }
    public function attributes()
    {
    	return $this->hasMany('App\Attribute');
    }
    public function outputs()
    {
    	return $this->hasMany('App\Output');
    }
    public function payload()
    {
        return $this->hasOne('App\Payload');
    }
    public static function boot() {
        parent::boot();

        static::deleting(function($transaction) { // before delete() method call this
             $transaction->attributes()->delete();
             $transaction->outputs()->delete();
             $transaction->payload()->delete();
             // do the rest of the cleanup...
        });
    } 

}

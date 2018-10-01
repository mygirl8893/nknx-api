<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['hash'];
    public function header()
    {
        return $this->hasOne('App\Header');
    }
    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }
    public static function boot() {
        parent::boot();

        static::deleting(function($block) { // before delete() method call this
             $block->header()->delete();
             $block->transactions()->delete();
             // do the rest of the cleanup...
        });
    }
    
}

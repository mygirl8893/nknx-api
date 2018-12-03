<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Block extends Model
{
    protected $fillable = ['hash','height','prevBlockHash','nextBlockHash','signature','signer','timestamp','transactionsRoot','version','winningHash','winningHashType','code','parameter','transaction_count'];

    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }
    public static function boot() {
        parent::boot();

        static::deleting(function($block) { // before delete() method call this
             $block->transactions()->delete();
             // do the rest of the cleanup...
        });
    }
    
}

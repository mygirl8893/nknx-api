<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['ID','label','online','State','SyncState','Port','NodePort','ChordPort','JsonPort','WsPort','Addr','Time','Version','Services','Relay','Height','TxnCnt','RxTxnCnt','ChordID','SoftwareVersion','LatestBlockHeight','user_id'];
    
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}


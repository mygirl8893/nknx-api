<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['label','online','State','SyncState','Port','NodePort','ChordPort','JsonPort','WsPort','Addr','Time','Version','Services','Relay','Height','TxnCnt','RxTxnCnt','ChordID','SoftwareVersion','LatestBlockHeight','user_id','sversion'];


    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function getAddrAttribute($value)
    {
        preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $value, $matches);
        return($matches[0]);
    }
}


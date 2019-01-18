<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    protected $fillable = ['label','online','syncState','jsonRpcPort','addr','version','height','latestBlockHeight','user_id','sversion','httpProxyPort','websocketPort','relayMessageCount'];


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


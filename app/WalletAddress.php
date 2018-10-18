<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletAddress extends Model
{
    protected $fillable = ['label','address','balance','user_id'];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationsConfig extends Model
{
    protected $fillable = ['user_id','nodeOffline','nodeOutdated', 'nodeStucked','weeklyMiningOutput'];
    protected $table = 'notifications_config';

    public function user()
    {
    	return $this->belongsTo('App\User');
    }
}

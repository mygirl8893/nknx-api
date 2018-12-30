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
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id','user_id', 'created_at', 'updated_at'
    ];
}

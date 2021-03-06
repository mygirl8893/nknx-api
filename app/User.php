<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function verifyUser()
    {
        return $this->hasOne('App\VerifyUser');
    }
    public function passwordReset()
    {
        return $this->hasOne('App\PasswordReset');
    }
    public function nodes()
    {
    	return $this->hasMany('App\Node');
    }
    public function walletAddresses()
    {
    	return $this->hasMany('App\WalletAddress');
    }
    public function notifications_config()
    {
        return $this->hasOne('App\NotificationsConfig');
    }
}

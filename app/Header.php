<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Header extends Model
{
    protected $fillable = ['chordId','hash','height','prevBlockHash','signature','signer','timestamp','transactionsRoot','version','winningHash','winningHashType'];
    public function block()
    {
    	return $this->belongsTo('App\Block');
    }
    public function program()
    {
        return $this->hasOne('App\Program');
    }
    public function setTimestampAttribute($value)
    {
        $this->attributes['timestamp'] = Carbon::createFromTimestamp($value)->toDateTimeString();
    }
    public static function boot() {
        parent::boot();

        static::deleting(function($header) { // before delete() method call this
             $header->program()->delete();
             // do the rest of the cleanup...
        });
    }
}

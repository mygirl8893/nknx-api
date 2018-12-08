<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NodeTracing extends Model
{
    protected $fillable = ['node_pk','priority'];
    protected $table = 'node_tracing';

    public function transaction()
    {
    	return $this->belongsTo('App\Transaction');
    }
        /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'transaction_id', 'created_at', 'updated_at'
    ];
}

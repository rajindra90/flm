<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    /**
     * @var string users
     */
    protected $table = 'friends';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','friend_id','request_token','is_accepted'
    ];
}

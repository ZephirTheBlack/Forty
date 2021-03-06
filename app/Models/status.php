<?php

namespace App\Models;

use App\Traits\HasLikes;
use App\User;
use Illuminate\Database\Eloquent\Model;

class status extends Model
{
    use HasLikes;

    protected $guarded=[];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public  function comments(){
        return $this->hasMany(Comment::class);
    }
    public function path(){
        return route('statuses.show',$this);
    }
}

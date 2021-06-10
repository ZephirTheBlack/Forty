<?php

namespace App;
use DB;
use App\Models\Friendship;
use App\Models\status;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use function Couchbase\defaultDecoder;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    protected $appends=['avatar'];

    //public $avatar='https://aprendible.com/images/default-avatar.jpg';
    public $avatar='/storage/default-avatar.jpg';

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function link(){
        return route('users.show',$this);
    }

    public function avatar(){
        $img=json_decode( json_encode(DB::table('users')->where('name','=',$this->attributes['name'])->get('avatar')), true);
        //dd($img[0]['avatar']);
        //return $img[0]['avatar'];
        return $this->avatar;
    }

    public function getAvatarAttribute(){
        return $this->avatar();
    }

    public function statuses(){
        return $this->hasMany(Status::class);
    }

    public function friendshipRequestReceived(){
        return $this->hasMany(Friendship::class,'recipient_id');
    }
    public function friendshipRequestSent(){
        return $this->hasMany(Friendship::class,'sender_id');
    }

    public function sendFriendRequestTo($recipient){
        return  $this->friendshipRequestSent()->firstOrCreate([
              'recipient_id'=>$recipient->id]);
    }

    public function acceptFriendRequestFrom($sender){

        $friendship=$this->friendshipRequestReceived()->where([
            'sender_id'=>$sender->id,])->first();

         $friendship->update(['status'=>'accepted']);

         return $friendship;
    }

    public function denyFriendRequestFrom($sender){

        $friendship=$this->friendshipRequestReceived()->where([
            'sender_id'=>$sender->id,])->first();

        $friendship->update(['status'=>'denied']);

        return $friendship;
    }
    public function friends(){
        $senderFriends = $this->belongsToMany(User::class, 'friendships', 'sender_id','recipient_id')
            ->wherePivot('status','accepted')->get();

        $recipientFriends = $this->belongsToMany(User::class, 'friendships', 'recipient_id','sender_id')
            ->wherePivot('status','accepted')->get();

        return $senderFriends->merge($recipientFriends);
    }
}

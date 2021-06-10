<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\User;
use Illuminate\Http\Request;

class AcceptFriendshipsController extends Controller
{
    public function index(Request $request){

        return view('friendships.index',[
            'friendshipRequest'=>$request->user()->friendshipRequestReceived
        ]);
    }

    public function store(User $sender, Request $request){


        $request->user()->acceptFriendRequestFrom($sender);

        return response()->json([
            'friendship_status' => 'accepted'
        ]);
    }

    public function destroy(User $sender, Request $request){

        $request->user()->denyFriendRequestFrom($sender);

        return response()->json([
            'friendship_status' => 'denied'
        ]);
    }

}

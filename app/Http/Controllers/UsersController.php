<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Friendship;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function show(User $user){


        return view('users.show',compact('user'));
    }

    public function edit(User $user){

        return view('users.edit',compact('user'));
    }

    public function update(User $user,Request $request){

        if ($request->hasFile('avatar')){
            $user->avatar = $user->avatar;
            $user->avatar = $request->file('avatar')->store('public');

        }

        $user-> update([
            'first_name'=> request('first_name'),
            'last_name'=> request('last_name'),
            'email'=> request('email'),
            'avatar'=>$user->avatar
        ]);

        if ( !empty(request('password')) && !empty(request('password_confirmation')) ){
            if (request('password')===request('password_confirmation')){
                $user->update(['password'=> request('password')]);
            }
        }

        return view('users.edit',compact('user'));
    }

}

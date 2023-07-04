<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserDataRequest;
use App\Http\Resources\LikeResource;
use App\Http\Resources\UserCommentsResource;
use App\Http\Resources\UserResource;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function  editUserData(UpdateUserDataRequest $request)
    {
        $user = User::find(Auth::id());
        $user->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'password' => $request->password
        ]);

        return new UserResource($user);

    }



    public function showUserData()
    {
        return new UserResource(Auth::user());
    }


    public function showLikedPosts()
    {
        $user = Auth::user();
        return LikeResource::collection($user->likes()->get());
    }



    public function showCommentedPosts()
    {
        $user = Auth::user();
        return UserCommentsResource::collection( $user->comments()->get());
    }
}

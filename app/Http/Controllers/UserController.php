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

/**
 * @OA\Schema(
 *     schema="User",
 *     required={"firstname", "lastname", "login"},
 *     @OA\Property(property="firstname", type="string", example="Barry"),
 *     @OA\Property(property="lastname", type="string", example="Berkman"),
 *     @OA\Property(property="login", type="string", example="bberkman@gmail.com")
 * )
 * @OA\Schema(
 *     schema="Comment",
 *     required={"id", "text", "user_firstname", "user_lastname", "post_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="text", type="string", example="That`s awesome"),
 *     @OA\Property(property="user_firstname", type="string", example="Walter"),
 *     @OA\Property(property="user_lastname", type="string", example="White"),
 *     @OA\Property(property="post_id", type="integer", example=1)
 * )
 * @OA\Schema(
 *     schema="Like",
 *     required={"user_id", "post"},
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/PostList"),
 * )
 */
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

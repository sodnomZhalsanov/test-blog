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
 *     required={"id", "firstname","lastname", "login", "password"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="firstname", type="string", example="Lohn"),
 *     @OA\Property(property="lastname", type="string", example="Forn"),
 *     @OA\Property(property="login", type="string", example="ddfgfflogin@mail.ru"),
 *     @OA\Property(property="password", type="string", example="audi5566"),
 *
 * ),
 *  * @OA\Schema(
 *     schema="Comment",
 *     required={"id", "text", "user_id", "post_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="text", type="string", example="gffgfffff fdd"),
 *     @OA\Property(property="post_id",type="integer", example=1),
 *
 * ),
 * @OA\Schema(
 *     schema="Like",
 *     required={"id","user_id", "post_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="post_id",type="integer", example=1),
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @OA\Put(
     *     path="/user",
     *     summary="update a user",
     *     description="update a user",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="firstname", type="string", example="Lohn"),
     *             @OA\Property(property="lastname", type="string", example="Forn"),
     *             @OA\Property(property="password", type="string", example="audi5566")
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

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

    /**
     * @OA\Get (
     *     path="/user",
     *     summary="show a user",
     *     description="show a user",
     *     tags={"User"},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/User"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="not found"
     *     )
     * )
     */

    public function showUserData()
    {
        return new UserResource(Auth::user());
    }

    /**
     * @OA\Get (
     *     path="/likes",
     *     summary="show user's likes",
     *     description="show user's likes",
     *     tags={"Like"},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Like")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="not found"
     *     )
     * )
     */

    public function showLikedPosts()
    {
        $user = Auth::user();
        return LikeResource::collection($user->likes()->get());
    }

    /**
     * @OA\Get (
     *     path="/comments",
     *     summary="show user's comments",
     *     description="show user's comments",
     *     tags={"Comment"},
     *
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Comment")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="not found"
     *     )
     * )
     */

    public function showCommentedPosts()
    {
        $user = Auth::user();
        return UserCommentsResource::collection( $user->comments()->get());
    }
}

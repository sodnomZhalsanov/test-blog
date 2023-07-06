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
 *     schema="UserComment",
 *     required={"id", "text", "user_firstname", "user_lastname", "link"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="text", type="string", example="That`s awesome"),
 *     @OA\Property(property="user_firstname", type="string", example="Walter"),
 *     @OA\Property(property="user_lastname", type="string", example="White"),
 *     @OA\Property(property="link", type="string", example="http://localhost/api/posts/1")
 * )
 * @OA\Schema(
 *     schema="UserLike",
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

    /**
     * @OA\Put(
     *     path="/user",
     *     summary="Update a user",
     *     description="Update a user",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="firstname", type="string", example="Barry"),
     *             @OA\Property(property="lastname", type="string", example="Berkman"),
     *             @OA\Property(property="password", type="string", example="asflkc42212")
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized"
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
     * @OA\Get(
     *     path="/user",
     *     summary="Show user",
     *     description="Show user",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="firstname", type="string", example="Barry"),
     *             @OA\Property(property="lastname", type="string", example="Berkman"),
     *             @OA\Property(property="password", type="string", example="asflkc42212")
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized"
     *     )
     * )
     */
    public function showUserData()
    {
        return new UserResource(Auth::user());
    }

    /**
     * @OA\Get(
     *     path="/likes",
     *     summary="Show liked posts",
     *     description="List of user`s likes",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="List of liked posts",
     *                 @OA\Items(
     *                    ref="#/components/schemas/UserLike"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized"
     *     )
     * )
     */
    public function showLikedPosts()
    {
        $user = Auth::user();
        return LikeResource::collection($user->likes()->get());
    }


    /**
     * @OA\Get(
     *     path="/comments",
     *     summary="Show commented posts",
     *     description="List of user comments",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="List of commented posts",
     *                 @OA\Items(
     *                    ref="#/components/schemas/UserComment"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="not found"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthorized"
     *     )
     * )
     */
    public function showCommentedPosts()
    {
        $user = Auth::user();
        return UserCommentsResource::collection( $user->comments()->get());
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\DeletePostRequest;
use App\Http\Requests\LikeRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\LikeResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostsListResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Schema(
 *     schema="PostList",
 *     required={"id", "title", "text", "category", "comments_count", "likes_count"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="group foto"),
 *     @OA\Property(property="text", type="string", example="head too heavy"),
 *     @OA\Property(property="category", type="string", example="summer"),
 *     @OA\Property(property="comments_count", type="integer", example=1),
 *     @OA\Property(property="likes_count", type="integer", example=1)
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
 *     schema="Post",
 *     required={"title", "text", "category", "comments_count", "likes_count"},
 *     @OA\Property(property="title", type="string", example="group foto"),
 *     @OA\Property(property="text", type="string", example="head too heavy"),
 *     @OA\Property(property="user_firstname", type="string", example="Walter"),
 *     @OA\Property(property="user_lastname", type="string", example="White"),
 *     @OA\Property(property="comments", type="array", description="List of comments",
 *                  @OA\Items(
 *                    ref="#/components/schemas/Comment"
 *                )
 * )
 * )
 * @OA\Schema(
 *     schema="Like",
 *     required={"user_id", "post"},
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="post", type="object", ref="#/components/schemas/PostList"),
 * )
 */
class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="show posts",
     *     description="Display a listing of posts.",
     *     tags={"Post"},
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 description="List of posts",
     *                  @OA\Items(
     *                    ref="#/components/schemas/PostList"
     *                )
     *         )
     * )
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
    public function showPosts()
    {
        return PostsListResource::collection(Post::orderBy('created_at', 'desc')->orderBy('id', 'desc')->cursorPaginate(10));
    }

    /**
     * @OA\Post(
     *     path="/posts",
     *     summary="Create a post",
     *     description="Create a new post",
     *     tags={"Post"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="I finally got a job"),
     *             @OA\Property(property="text", type="string", example="i am so glad"),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Post"
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
    public function createPost(CreatePostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'text' => $request->text,
            'category_id' => $request->category_id,
            'user_id' => Auth::id()
        ]);

        return new PostResource($post);
    }

    /**
     * @OA\Get(
     *     path="/posts/{id}",
     *     summary="show post",
     *     description="Display a chosen post.",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Post"
     *             )
     *         )
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
    public function showPost($id)
    {
        $post = Post::find($id);


        return new PostResource($post);

    }

    /**
     * @OA\Put(
     *     path="/posts/{id}",
     *     summary="Update a post",
     *     description="Update a post",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="I finally got a job"),
     *             @OA\Property(property="text", type="string", example="i am so glad"),
     *             @OA\Property(property="category_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Post"
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
    public function updatePost(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        $post->update(['title' => $request->title, 'text' => $request->text, 'category_id' => $request->category_id]);

        return new PostResource($post);

    }

    /**
     * @OA\Delete(
     *     path="/posts/{id}",
     *     summary="Delete a post",
     *     description="Delete a post",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true
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
    public function deletePost(DeletePostRequest $request, $id)
    {
        $post = Post::find($id);
        $post->delete();

    }

    /**
     * @OA\Post(
     *     path="/posts/{id}/comment",
     *     summary="comment a post",
     *     description="comment a post",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="text", type="string", example="i am so glad")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Comment"
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
    public function comment(CommentRequest $request, $id)
    {
        $comment = Comment::create([
            'text' => $request->text,
            'user_id' => Auth::id(),
            'post_id' => $id
        ]);

        return new CommentResource($comment);

    }

    /**
     * @OA\Post(
     *     path="/posts/{id}/like",
     *     summary="like a post",
     *     description="like a post",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Like"
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
    public function like(LikeRequest $request)
    {
        $userId = Auth::id();

        $postId = $request->post_id;


        $like = Like::firstOrCreate([
            'user_id' => $userId,
            'post_id' => $postId
        ]);

        return new LikeResource($like);


    }

    /**
     * @OA\Post(
     *     path="/posts/{id}/dislike",
     *     summary="dislike a post",
     *     description="dislike a post",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="post_id", type="integer", example=1)
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
    public function dislike(LikeRequest $request)
    {
        $userId = Auth::id();

        $postId = $request->post_id;


        if ($like = Like::where('post_id', $postId)->where('user_id', $userId)->first()) {
            $like->delete();

        }
    }
}

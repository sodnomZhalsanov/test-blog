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
use App\Http\Resources\PostResource;
use App\Http\Resources\PostsListResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @OA\Get(
     *     path="/posts",
     *     summary="show all posts",
     *     description="show all posts in paginated order",
     *     tags={"Post"},
     *
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
     *         description="Unauthorized"
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
     *     summary="create a post",
     *     description="create a new post",
     *     tags={"Post"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="dregory"),
     *             @OA\Property(property="text", type="string", example="dsdc dsdc dd!"),
     *             @OA\Property(property="category_id", type="integer", example=1)
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
     *                 ref="#/components/schemas/Post"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
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
     *     path="/posts/{{id}}",
     *     summary="show a post",
     *     description="show a post with id placed in url",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
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
     *     path="/posts/{{id}}",
     *     summary="update a post",
     *     description="update a post with id placed in url",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="dregory"),
     *             @OA\Property(property="text", type="string", example="dsdc dsdc dd!"),
     *             @OA\Property(property="category_id", type="integer", example=1)
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
     *                 ref="#/components/schemas/Post"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
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
     *     path="/posts/{{id}}",
     *     summary="delete a post",
     *     description="delete a post with id placed in url",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation"
     *
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
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
     *     path="/posts/{{id}}/comment",
     *     summary="create a comment",
     *     description="create a new comment attached to the post with id placed in url",
     *     tags={"Comment"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="text", type="string", example="gffgfffff fdd"),
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
     *                 ref="#/components/schemas/Comment"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
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
     *     path="/posts/{{id}}/like",
     *     summary="create a like",
     *     description="create a new like ",
     *     tags={"Like"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="post_id",type="integer", example=1),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 ref="#/components/schemas/Like"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function like(LikeRequest $request)
    {
        $user_id = Auth::id();

        $post_id = $request->post_id;

        if (!$like = Like::where('post_id', $post_id)->where('user_id', $user_id)->first()) {
            Like::create([
                'user_id' => $user_id,
                'post_id' => $post_id
            ]);

            return response()->json([
                'message' => 'You liked this post',
            ]);
        } else {
            return response()->json([
                'message' => 'You already liked this post',
            ]);
        }


    }

    /**
     * @OA\Post(
     *     path="/posts/{{id}}/dislike",
     *     summary="dislike",
     *     description="delete a like ",
     *     tags={"Like"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the post",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="post_id",type="integer", example=1),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 ref="#/components/schemas/Like"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */

    public function dislike(LikeRequest $request)
    {
        $user_id = Auth::id();

        $post_id = $request->post_id;


        if ($like = Like::where('post_id', $post_id)->where('user_id', $user_id)->first()) {
            $like->delete();
            return response()->json([
                'message' => 'You disliked this post',
            ]);
        } else {
            return response()->json([
                'message' => 'You did not like this post before',
            ]);
        }
    }
}

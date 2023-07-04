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

class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function showPosts()
    {
        return PostsListResource::collection(Post::orderBy('created_at', 'desc')->orderBy('id', 'desc')->cursorPaginate(10));
    }


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


    public function showPost($id)
    {
        $post = Post::find($id);


        return new PostResource($post);

    }


    public function updatePost(UpdatePostRequest $request, $id)
    {
        $post = Post::find($id);

        $post->update(['title' => $request->title, 'text' => $request->text, 'category_id' => $request->category_id]);

        return new PostResource($post);

    }


    public function deletePost(DeletePostRequest $request, $id)
    {
        $post = Post::find($id);
        $post->delete();

    }


    public function comment(CommentRequest $request, $id)
    {
        $comment = Comment::create([
            'text' => $request->text,
            'user_id' => Auth::id(),
            'post_id' => $id
        ]);

        return new CommentResource($comment);

    }


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


    public function dislike(LikeRequest $request)
    {
        $userId = Auth::id();

        $postId = $request->post_id;


        if ($like = Like::where('post_id', $postId)->where('user_id', $userId)->first()) {
            $like->delete();

        }
    }
}

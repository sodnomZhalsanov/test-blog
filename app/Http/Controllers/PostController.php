<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreatePostRequest;
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
    public function showPosts()
    {
        return PostsListResource::collection(Post::orderBy('created_at','desc')->cursorPaginate(10));
    }
    public function createCategory(CreateCategoryRequest $request)
    {
        $category = Category::create(['title' => $request->title, 'user_id' => $request->user_id]);

        return new CategoryResource($category);
    }

    public function createPost(CreatePostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'text' => $request->text,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'post' => new PostResource($post),
        ]);

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

    public function deletePost($id)
    {
        $post = Post::find($id);
        $post->delete();

    }

    public function comment(CommentRequest $request, $id)
    {
        $comment = Comment::create([
            'text' => $request->text,
            'user_id' => $request->user_id,
            'post_id' => $id
        ]);

        return new CommentResource($comment);

    }

    public function like($id)
    {

        $post = Post::find($id);

        $user_id = Auth::id();

        if($post->user_id === $user_id){
            return response()->json([
                'message' => 'You can not like your posts',
            ]);
        }


        if (!$like = Like::where('post_id', $id)->where('user_id', $user_id)->first()) {
            Like::create([
                'user_id' => $user_id,
                'post_id' => $id
            ]);
        } else {
            $like->delete();
            return response()->json([
                'message' => 'You disliked this post',
            ]);
        }

        return response()->json([
            'message' => 'You liked this post',
        ]);


    }
}

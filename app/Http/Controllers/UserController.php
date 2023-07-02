<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function showPosts()
    {
        //$posts = Post::orderBy('created_at','desc')->cursorPaginate(10);

        // return response()->json([
        //    'status' => 'success',
        //    'posts' => $posts
        //]);

        return PostResource::collection(Post::orderBy('created_at','desc')->cursorPaginate(10));
    }
    public function createCategory(CreateCategoryRequest $request)
    {
        $category = Category::create(['title' => $request->title, 'user_id' => $request->user_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'category' => $category,
        ]);
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
            'post' => $post,
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

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated successfully',
            'post' => $post,
        ]);


    }

    public function deletePost($id)
    {
        $post = Post::find($id);
        $post->delete();

    }

    public function  editUserData()
    {

    }

    public function showUserData()
    {

    }

    public function comment(CommentRequest $request)
    {
        $comment = Comment::create([$request->all()]);

        return new CommentResource($comment);

    }

    public function like()
    {

    }
}

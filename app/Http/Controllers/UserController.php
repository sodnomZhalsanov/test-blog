<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
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
        $posts = Post::orderBy('created_at','desc')->cursorPaginate(10);

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ]);
    }
    public function createCategory(CreateCategoryRequest $request)
    {
        $category = Category::create([$request->all()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'todo' => $category,
        ]);
    }

    public function createPost(CreatePostRequest $request)
    {
        $post = Post::create([$request->all()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
            'post' => $post,
        ]);

    }

    public function showPost($post_id)
    {
        $post = Post::find($post_id);

        $comments = $post->comments();

        $author = $post->user();

        return response()->json([
            'status' => 'success',
            'title' => $post->title,
            'text' => $post->text,
            'firstname' => $author->firstname,
            'lastname' => $author->lastname,
            'comments' => $comments
        ]);

    }

    public function updatePost(UpdatePostRequest $request, $post_id)
    {
        $post = Post::find($post_id);
        $post->update(['title' => $request->title, 'text' => $request->text, 'category_id' => $request->category_id]);

        return response()->json([
            'status' => 'success',
            'message' => 'Post updated successfully',
            'post' => $post,
        ]);


    }

    public function deletePost($post_id)
    {
        $post = Post::find($post_id);
        $post->delete();

    }

    public function comment($post_id)
    {

    }
}

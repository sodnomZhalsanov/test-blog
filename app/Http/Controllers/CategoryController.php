<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function createCategory(CreateCategoryRequest $request)
    {
        $category = Category::create(['title' => $request->title, 'user_id' => Auth::id()]);

        return new CategoryResource($category);
    }
}

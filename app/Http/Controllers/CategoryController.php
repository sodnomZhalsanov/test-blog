<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * @OA\Schema(
 *     schema="Category",
 *     required={"title", "user_id"},
 *     @OA\Property(property="title", type="string", example="having fun"),
 *     @OA\Property(property="user_id", type="integer", example=1)
 * )
 */
class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * @OA\Post(
     *     path="/category",
     *     summary="Create a category",
     *     description="Create a new category",
     *     tags={"Category"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="having fun")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Category"
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
    public function createCategory(CreateCategoryRequest $request)
    {
        $category = Category::create(['title' => $request->title, 'user_id' => Auth::id()]);

        return new CategoryResource($category);
    }
}

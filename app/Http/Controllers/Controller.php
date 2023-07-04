<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      title="Blog",
 *      version="1.0.0",
 *      @OA\Contact(
 *          email="admin@example.com"
 *      )
 *
 * )
 * @OA\Tag(
 *     name = "Auth",
 *     description = "Auth description",
 * )
 * @OA\Server(
 *     description="Laravel Swagger API Server",
 *     url="http://localhost:82/api"
 * )
 *
 *

 */
class Controller extends BaseController
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

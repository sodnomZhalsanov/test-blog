<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Like::class;
    public function definition()
    {
        $user_id = User::get()->random()->id;
        return [
            'user_id' =>$user_id ,
            'post_id' => Post::where('user_id','!=',$user_id )->get()->random()->id,
        ];
    }
}

<?php

namespace App\Rules;

use App\Models\Post;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Auth;

class UserLikeRule implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $userId = Auth::id();
        $post = Post::find($value);

        if($post->user_id === $userId){
            $fail('You can not like or dislike your posts');
        }
    }
}

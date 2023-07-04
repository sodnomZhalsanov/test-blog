<?php

namespace App\Http\Requests;

use App\Rules\PostExists;
use App\Rules\UserLikeRule;
use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'post_id' => ['bail','required', 'exists:posts,id', new UserLikeRule]
        ];
    }


}

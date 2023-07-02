<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'text' => $this->text,
            'user_firstname' => $this->user->firstname,
            'user_lastname' => $this->user->lastname,
            'comments' => CommentResource::collection($this->comments)
        ];
    }
}

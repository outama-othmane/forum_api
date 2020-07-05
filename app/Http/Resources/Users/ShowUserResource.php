<?php

namespace App\Http\Resources\Users;

use App\Http\Resources\Discussions\DiscussionResource;
use App\Http\Resources\Posts\PostResource;
use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowUserResource extends UserResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'posts_count' => $this->posts_count,
            'discussions_count' => $this->discussions_count,
            /*-------------------------------------------------------
            'discussions' => $this->merge(
                $this->whenLoaded('discussions', DiscussionResource::collection($this->discussions))
            ),
            'posts' => $this->merge(
                $this->whenLoaded('posts', PostResource::collection($this->posts))
            ),
            -------------------------------------------------------*/
        ]);
    }
}

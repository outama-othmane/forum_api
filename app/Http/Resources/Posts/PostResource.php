<?php

namespace App\Http\Resources\Posts;

use App\Http\Resources\Users\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'content'           => $this->content,
            'content_markdown'  => $this->content_markdown,
            'created_at'        => $this->created_at,
            'created_at_humans' => $this->created_at->diffForHumans(),
            'isParent'          => $this->isParent,
            'parent_id'         => $this->parent_id,
            'votes_count'       => $this->votes_count,
            'author'            => $this->merge(
                $this->whenLoaded('user', new UserResource($this->user))
            ),
            'user'          => [
                'isLiked'   => $this->isLiked(),
                'canEdit'   => $this->canEdit(),
                'canDelete' => $this->canDelete(),
            ],
        ];
    }
}

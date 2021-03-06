<?php

namespace App\Http\Resources\Discussions;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchDiscussionResource extends JsonResource
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
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'posts_count'   => ($this->posts_count <= 0) ? 0 : $this->posts_count-1,
            'created_at'        => $this->created_at,
            'created_at_humans' => $this->created_at->diffForHumans(),
            'isClosed'      => $this->isClosed,
            'closed_at'     => optional($this->closed_at)->diffForHumans(),
            'user'          => [
                'canDelete' => $this->canDelete(),
                'canEdit'  => $this->canEdit(),
            ]
        ];
    }
}

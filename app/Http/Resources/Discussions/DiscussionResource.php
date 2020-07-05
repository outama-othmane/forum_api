<?php

namespace App\Http\Resources\Discussions;

use App\Http\Resources\Channels\ChannelResource;
use App\Http\Resources\Posts\PostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscussionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // To stop
        // I forgot the reason why I added this
        if ($this->lastPost) {
            $this->lastPost->setRelation('discussion', $this);
        }

        return [
            'id'            => $this->id,
            'title'         => $this->title,
            'slug'          => $this->slug,
            'posts_count'   => $this->posts_count-1,
            'created_at'        => $this->created_at,
            'created_at_humans' => $this->created_at->diffForHumans(),
            'isClosed'      => $this->isClosed,
            'closed_at'     => optional($this->closed_at)->diffForHumans(),
            'channels'      => $this->merge(
                $this->whenLoaded('channel', new ChannelResource($this->channel))
            ),
            'last_post'     => $this->merge(
                $this->whenLoaded('lastPost', new PostResource($this->lastPost))
            ),
        ];
    }
}

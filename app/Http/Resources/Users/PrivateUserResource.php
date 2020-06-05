<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

class PrivateUserResource extends JsonResource
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
            'name'        => $this->name,
            'email'             => $this->email,
            'email_verified'    => (bool)$this->email_verified_at,
            'avatar'            => $this->avatar,
            'created_at'        => $this->created_at->diffForHumans(),
        ];
    }
}

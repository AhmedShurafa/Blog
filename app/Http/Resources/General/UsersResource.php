<?php

namespace App\Http\Resources\General;

use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
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
            'name'       => $this->name,
            'username'   => $this->username,
            'status'     => $this->status(),
            'user_image' => $this->userImage(),
            'bio'        => $this->bio,
        ];
    }
}
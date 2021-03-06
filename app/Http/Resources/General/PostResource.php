<?php

namespace App\Http\Resources\General;

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
            'title'        => $this->title,
            'slug'         => $this->slug,
            'description'  => $this->description,
            'status'       => $this->status(),
            'comment_able' => $this->comment_able,
            'create_date'  => $this->created_at->format('d-m-Y h:i a'),
            'category'     => new CategoriesResource($this->category),
            'user'         => new UsersResource($this->user),
            'tags'         => TagsResource::collection($this->tags),
        ];
    }
}

<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // "data" => [
            //     "id" => $this->id,
            //     "title" => $this->title,
            //     "slug" => $this->slug,
            //     "body" => $this->body,
            // ],
            // "relationships" => [
            //     "author_id" => $this->user->id,
            //     "author" => $this->user->name,
            // ],

            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "body" => $this->body,
            "user_id" => $this->user->id,
            "author" => $this->user->name,
        ];
    }
}

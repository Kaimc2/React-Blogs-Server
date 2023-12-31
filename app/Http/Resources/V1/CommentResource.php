<?php

namespace App\Http\Resources\V1;

use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $likes = Like::with('comment')->where('comment_id', '=', $this->id)->count();

        return [
            "comment_id" => $this->id,
            "comment" => $this->content,
            "commenter_id" => $this->user->id,
            "commenter" => $this->user->name,
            "commenter_pf" => 'storage/profiles/' . $this->user->profile,
            "created_at" => $this->updated_at->diffForHumans(),
            "likes" => $likes
        ];
    }
}

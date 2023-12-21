<?php

namespace App\Http\Resources\V1;

use App\Models\Comment;
use App\Models\Like;
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
        $latestComments = Comment::latest()->with('user')->where('post_id', '=', $this->id)->get();
        $like = Like::with('post')->where('post_id', '=', $this->id)->count();

        return [
            "post" => [
                "id" => $this->id,
                "title" => $this->title,
                "thumbnail" => "storage/thumbnails/" . $this->thumbnail,
                "slug" => $this->slug,
                "category" => $this->category->name,
                "category_id" => $this->category->id,
                "body" => $this->body,
                "createdAt" => $this->created_at->format('M d, Y'),
            ],
            "relationships" => [
                "author_id" => $this->author_id,
                "author_pf" => "storage/profiles/" . $this->user->profile,
                "author" => $this->user->name,
                "comments" => $this->comments->isEmpty() ? [] : CommentResource::collection($latestComments),
                "likes" => $like, 
            ]
        ];
    }
}

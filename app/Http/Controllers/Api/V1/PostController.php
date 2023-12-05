<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Http\Resources\V1\PostResource;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return PostResource::collection($posts);
    }

    public function show(Post $post)
    {
        if (!$post) {
            return response()->json(["message" => "Post not found"], 404);
        }

        return new PostResource($post);
    }

    public function store(StorePostRequest $request)
    {
        Post::create($request->validated());
        return response()->json(["message" => "Post Created"]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        if (!$post) {
            return response()->json(["message" => "Post not found"], 404);
        }
        
        if ($post->user_id != $request->user()->id) {
            return response()->json(["message" => "Unauthorized"], 403);
        }

        $post->update($request->validated());

        return response()->json(["message" => "Post Updated"]);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(["message" => "Post not found"], 404);
        }
        $post->delete();

        return response()->json("Post Deleted");
    }
}

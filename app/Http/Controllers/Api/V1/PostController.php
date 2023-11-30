<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Models\Posts;
use App\Http\Resources\V1\PostResource;

class PostController extends Controller
{
    public function index() {
        // return PostResource::collection(Posts::latest()->paginate(10));
        $posts = Posts::latest()->paginate(10);
        return PostResource::collection($posts);
    }

    public function show(Posts $post) {
        return new PostResource($post);
    }

    public function store(StorePostRequest $request) {
        Posts::create($request->validated());
        return response()->json("Post Created");
    }

    public function update(StorePostRequest $request, Posts $post) {
        $post->update($request->validated());
        return response()->json("Post Updated");
    }

    public function destroy($id) {
        $post = Posts::findOrFail($id);
        $post->delete();
        return response()->json("Post Deleted");
    }
}

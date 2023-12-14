<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Post;
use App\Http\Resources\V1\PostResource;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // $posts = Post::latest()->paginate(10);
        $posts = Post::when(
            $request->input('search'),
            fn ($query, $search) => $query->where('title', 'like', "%$search%")
        )
            ->when(
                $request->input('category'),
                fn ($query, $category) => $query->whereHas('category', function ($subQuery) use ($category) {
                    $subQuery->where('name', 'like', "%$category%");
                })
            )->with('category')->latest()->paginate(10);

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
        $request->validated();
        // return response()->json([
        //     'message' => 'Success',
        //     'request_data' => $request->all(),
        // ]);

        if ($request->hasFile("thumbnail")) {
            $thumbnail = $request->file("thumbnail");
            $fileName = time() . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail->storeAs('thumbnails', $fileName, 'public');
        }

        Post::create([
            "title" => $request->title,
            "thumbnail" => $fileName,
            "body" => $request->body,
            "category_id" => $request->category_id,
            "author_id" => $request->author_id,
        ]);

        return response()->json(["message" => "Post Created"]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $request->validated();
        // Debug the incoming request
        // return response()->json([
        //     'message' => 'Success',
        //     'request_data' => $request->all(),
        // ]);

        if (!$post) {
            return response()->json(["message" => "Post not found"], 404);
        }

        if ($request->hasFile("thumbnail")) {
            if (file_exists(public_path("storage/thumbnails/" . $post->thumbnail) && $post->thumbnail !== "placeholder.jpg")) {
                unlink(public_path("storage/thumbnails/" . $post->thumbnail));
            }

            $newThumbnail = $request->file("thumbnail");
            $newFileName = time() . '.' . $newThumbnail->getClientOriginalExtension();
            $newThumbnail->storeAs('thumbnails', $newFileName, 'public');
        } else {
            $newFileName = $post->thumbnail;
        }

        $post->update([
            'title' => $request->title,
            'body' => $request->body,
            'category_id' => $request->category_id,
            "thumbnail" => $newFileName,
        ]);

        return response()->json(["message" => "Post Updated"]);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(["message" => "Post not found"], 404);
        }

        if (file_exists(public_path("storage/thumbnails/" . $post->thumbnail))) {
            unlink(public_path("storage/thumbnails/" . $post->thumbnail));
        }

        $post->delete();

        return response()->json("Post Deleted");
    }

    public function category()
    {
        $categories = Category::all();
        return CategoryResource::collection($categories);
    }
}

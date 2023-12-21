<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::where("author_id", $request->user()->id)
            ->when($request->input('search'), fn ($query, $search) => $query->where('title', 'like', "%$search%"))
            ->get();

        return response()->json(["data" => PostResource::collection($posts), "message" => "Data fetched"]);
    }
}

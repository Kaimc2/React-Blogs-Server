<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = $request->validate([
            'content' => 'required|max:2000',
            'user_id' => 'required',
            'post_id' => 'required',
        ]);

        Comment::create($comment);

        return response()->json(["message" => "Comment successfully created", "data" => Comment::all()]);
    }

    public function update(Request $request, Comment $comment)
    {
        // dd($comment);
        $validatedComment = $request->validate([
            'content' => 'required|max:2000',
            'user_id' => 'required',
            'post_id' => 'required',
        ]);

        // dd($validatedComment);
        $comment->update($validatedComment);

        return response()->json(["message" => "Comment successfully created", "data" => Comment::all()]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment does not exist'], 404);
        }
        // dd($comment);

        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }
}

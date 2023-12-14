<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $this->validate($request, [
            "name" => "required|unique:users,name," . $user->id . ",id",
        ]);

        if ($request->hasFile("profile")) {
            $this->validate($request, [
                "profile" => "required|image|mimes:png,jpg,jpeg"
            ]);
        }


        if ($request->hasFile("profile")) {
            if (file_exists(public_path("storage/profiles/" . $user->profile))) {
                unlink(public_path("storage/profiles/" . $user->profile));
            }

            $file = $request->file("profile");
            $filename = time() . "." . $file->getClientOriginalExtension();
            $file->storeAs("profiles", $filename, "public");
        } else {
            $filename = $user->profile;
        }

        $user->update([
            "name" => $request->name,
            "profile" => $filename,
        ]);

        return response()->json([
            "profile" => "storage/profiles/" . $filename,
            "message" => "Update successfully"
        ]);
    }
}

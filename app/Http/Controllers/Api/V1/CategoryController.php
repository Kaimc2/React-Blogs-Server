<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::when(
            $request->input('search'), 
            fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))->get();

        return CategoryResource::collection($categories);
    }

    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found']);
        }

        return new CategoryResource($category);
    }

    public function store(Request $request)
    {
        $category = $request->validate(['name' => 'required|unique:categories,name,except,id']);

        Category::create($category);

        return response()->json(['message' => 'Category created successfully']);
    }

    public function update(Request $request, Category $category)
    {
        $updatedCategory = $request->validate(['name' => "required|unique:categories,name," . $category->id . ",id"]);

        $category->update($updatedCategory);

        return response()->json(['message' => 'Category update successfully']);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json(['message' => 'Category not found']);
        }

        $category->delete();

        return response()->json(['message' => 'Category delete successfully']);
    }
}

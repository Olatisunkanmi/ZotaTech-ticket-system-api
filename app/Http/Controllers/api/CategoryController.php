<?php

namespace App\Http\Controllers\api;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::latest()->paginate(5)->through(fn ($cat) => new CategoryResource($cat));

        return response()->json([
            'message' => "Categories listed successfully",
            'data' => $categories,
        ], 201);
    }


    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'message' => "Category Created successfully",
            'data' => $category,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function slug(string $category): JsonResponse
    {
        try {
            //code...
            $category = Category::where('name', $category)->firstOrFail();

            return response()->json([
                'data' => new CategoryResource($category),
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;

            return response()->json([
                'message' => "Category was not found",
            ], 404);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $category): JsonResponse
    {
        try {
            $category = Category::findorFail($category);

            // $category = 
            
            return response()->json([
                'message' => "Category was found",
                'data' => new CategoryResource($category),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "Category was not found ",
            ], 404);
        }
    }

    public function update(Request $request, int $category): JsonResponse
    {
        try {
            $category = Category::findOrFail($category);

            $category->update($request->all());

            return response()->json([
                'message' => "Category Updated successfully",
                'data' => $category,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => "No Category with this Id was found ",
            ], 404);
        };
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return response()->json([
            'message' => "Category delete successfully",
            'data' => $category,
        ], 200);
    }
}

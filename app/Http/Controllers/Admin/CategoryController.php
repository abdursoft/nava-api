<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestGame;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'code' => 'required|unique:categories,code',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Category couldn\'t create',
                'errors' => $validator->errors()
            ],400);
        }

        try {
            Category::create([
                'name' => $request->name,
                'code' => $request->code,
                'image' => Storage::disk('public')->put('category',$request->file('image'))
            ]);
            return response()->json([
                'code' => 'CATEGORY_CREATED',
                'message' => 'Category successfully created'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Category couldn\'t create',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id=null)
    {
        if($id === null){
            return response()->json([
                'code' => 'CATEGORY_RETRIEVED',
                'message' => 'Category successfully retrieve',
                'category' => Category::with('bestGame')->get()
            ],200);
        }else{
            return response()->json([
                'code' => 'CATEGORY_RETRIEVED',
                'message' => 'Category successfully retrieve',
                'category' => Category::with('bestGame')->find($id)
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'code' => 'required|unique:categories,code,'.$id.',id',
            'image' => 'file|mimes:jpeg,jpg,png,webp',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Category couldn\'t update',
                'errors' => $validator->errors()
            ],400);
        }
        $exists = Category::find($id);
        try {
            Category::where('id',$id)->update([
                'name' => $request->name,
                'code' => $request->code,
                'image' => $request->hasFile('image') ? Storage::disk('public')->put('category',$request->file('image')) : $exists->image
            ]);
            $exists ? Storage::disk('public')->delete($exists->image) : true;
            return response()->json([
                'code' => 'CATEGORY_UPDATED',
                'message' => 'Category successfully updated'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Category couldn\'t update',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = Category::find($id);
            BestGame::where('category_id',$id)->delete();
            if($category){
                Storage::disk('public')->delete($category->image);
            }
            $category->delete();
            return response()->json([
                'code' => 'CATEGORY_DELETED',
                'message' => 'Category successfully deleted',
            ],200);
        } catch (\Throwable $th) {
           return response()->json([
            'code' => 'INTERNAL_SERVER_ERROR',
            'message' => 'Category couldn\'t delete'
           ],400);
        }
    }
}

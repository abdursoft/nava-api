<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BestGame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
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
            'game_id' => 'required|unique:best_games,game_id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|file|mimes:jpeg,jpg,png,webp',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Game couldn\'t create',
                'errors' => $validator->errors()
            ],400);
        }

        try {
            BestGame::create([
                'name' => $request->name,
                'game_id' => $request->game_id,
                'category_id' => $request->category_id,
                'image' => Storage::disk('public')->put('games',$request->file('image'))
            ]);
            return response()->json([
                'code' => 'GAME_CREATED',
                'message' => 'Game successfully created'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Game couldn\'t create',
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
                'code' => 'GAME_RETRIEVED',
                'message' => 'Game successfully retrieve',
                'games' => BestGame::with('category')->get()
            ],200);
        }else{
            return response()->json([
                'code' => 'GAME_RETRIEVED',
                'message' => 'Game successfully retrieve',
                'games' => BestGame::with('category')->find($id)
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
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'game_id' => 'required|unique:best_games,game_id,'.$id.',id',
            'category_id' => 'required|exists:categories,id',
            'image' => 'file|mimes:jpeg,jpg,png,webp',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Game couldn\'t update',
                'errors' => $validator->errors()
            ],400);
        }
        $exists = BestGame::find($id);
        try {
            BestGame::where('id', $id)->update([
                'name' => $request->name,
                'game_id' => $request->game_id,
                'category_id' => $request->category_id,
                'image' => $request->hasFile('image') ? Storage::disk('public')->put('games',$request->file('image')) : $exists->image
            ]);
            $exists ? Storage::disk('public')->delete($exists->image) : true;
            return response()->json([
                'code' => 'GAME_UPDATED',
                'message' => 'Game successfully updated'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Game couldn\'t update',
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
            $game = BestGame::find($id);
            if($game){
                Storage::disk('public')->delete($game->image);
            }
            $game->delete();
            return response()->json([
                'code' => 'GAME_DELETED',
                'message' => 'GAME successfully deleted',
            ],200);
        } catch (\Throwable $th) {
           return response()->json([
            'code' => 'INTERNAL_SERVER_ERROR',
            'message' => 'Game couldn\'t delete'
           ],400);
        }
    }
}

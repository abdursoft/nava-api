<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DepositBonus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepositBonusController extends Controller
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
            'game' => 'required',
            'amount' => 'required',
            'message' => 'required',
            'turnover' => 'required',
            'minimum' => 'required',
            'status' => 'required',
            'limit' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Please fill-up the required fields',
                'errors' => $validator->errors()
            ],400);
        }

        try {
            DepositBonus::create($validator->validate());
            return response()->json([
                'status' => true,
                'message' => 'Bonus successfully created'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Deposit bonus couldn\'t create'
            ],400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id=null)
    {
        if($id===null){
            return response()->json([
                'status' => true,
                'bonus' => DepositBonus::all()
            ],200);
        }else{
            return response()->json([
                'status' => true,
                'bonus' => DepositBonus::find($id)
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Hd7]dB37:*95xfC(
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'game' => 'required',
            'amount' => 'required',
            'message' => 'required',
            'minimum' => 'required',
            'status' => 'required',
            'limit' => 'required',
            'image' => 'required|file|mimes:jpeg,webp,png,jpeg,jpg',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Please fill-up the required fields',
                'errors' => $validator->errors()
            ],400);
        }

        try {
            DepositBonus::where('id',$id)->update($validator->validate());
            return response()->json([
                'code' => 'BONUS_CREATED',
                'message' => 'Bonus successfully updated'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Deposit bonus couldn\'t update'
            ],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DepositBonus::where('id',$id)->delete();
            return response()->json([
                'code' => 'BONUS_RETRIEVED',
                'message' => 'Bonus successfully deleted'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Bonus couldn\'t delete'
            ],400);
        }
    }
}

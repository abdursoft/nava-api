<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class BalanceController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Balance $Balance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Balance $Balance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Balance $Balance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Balance $Balance)
    {
        //
    }

    /**
     * Player balance
     */
    public function playerBalance(Request $request, $playerId){
        if(!empty($playerId) && !empty($request->header('Pass-Key'))){
            $balance = User::where('user_id',$playerId)->first();
            return response()->json([
                "balance" => $balance->balance,
                "currency" => $balance->currency
            ],200);
        }else{
            return response()->json([
                "code" => "LOGIN_FAILED",
                "message" => "The given pass-key is incorrect"
            ],200);
        }
    }
}

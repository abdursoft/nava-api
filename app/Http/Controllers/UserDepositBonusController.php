<?php

namespace App\Http\Controllers;

use App\Models\UserDepositBonus;
use Illuminate\Http\Request;

class UserDepositBonusController extends Controller
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
    public function show(Request $request, $id=null)
    {
        if($id === null){
            return response()->json([
                'code' => 'USER_BONUS_RETRIEVED',
                'message' => 'User bonus successfully retrieved',
                'bonus' => UserDepositBonus::with('depositBonus')->where('user_id',$request->header('id'))->get()
            ],200);
        }else{
            return response()->json([
                'code' => 'USER_BONUS_RETRIEVED',
                'message' => 'User bonus successfully retrieved',
                'bonus' => UserDepositBonus::with('depositBonus')->where('user_id',$request->header('id'))->where('id', $id)->first()
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserDepositBonus $userDepositBonus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserDepositBonus $userDepositBonus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserDepositBonus $userDepositBonus)
    {
        //
    }
}

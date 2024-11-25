<?php

namespace App\Http\Controllers;

use App\Models\UserTurnOver;
use Illuminate\Http\Request;

class UserTurnOverController extends Controller
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

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id=null)
    {
        if($id === null){
            return response()->json([
                'code' => 'TURNOVER_RETRIEVED',
                'turnover' => UserTurnOver::where('user_id',$request->header('id'))->get()
            ],200);
        }else{
            return response()->json([
                'code' => 'TURNOVER_RETRIEVED',
                'turnover' => UserTurnOver::find($id)
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserTurnOver $userTurnOver)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserTurnOver $userTurnOver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserTurnOver $userTurnOver)
    {
        //
    }
}

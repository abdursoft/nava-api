<?php

namespace App\Http\Controllers;

use App\Models\ReferHistory;
use Illuminate\Http\Request;

class ReferHistoryController extends Controller
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
    public function show($id=null)
    {
        if($id === null){
            return response()->json([
                'code' => 'USER_REFER_HISTORY',
                'message' => 'Refer history retrieved',
                'refer' => ReferHistory::all()
            ],200);
        }else{
            return response()->json([
                'code' => 'USER_REFER_HISTORY',
                'message' => 'Refer history retrieved',
                'refer' => ReferHistory::find($id)
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReferHistory $referHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReferHistory $referHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReferHistory $referHistory)
    {
        //
    }
}

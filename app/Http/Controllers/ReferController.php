<?php

namespace App\Http\Controllers;

use App\Models\Refer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReferController extends Controller
{
    /**
     * Add new refer code for user|agent|manager
     */
    public function add(Request $request){
        $validator = Validator::make($request->all(),[
            'code' => 'required|unique:refers,refer_code',
            'amount' => 'required',
            'status' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Refer code couldn\'t create',
                'errors' => $validator->errors()
            ],400);
        }

        try {
            $id = $request->input('user_id');
            Refer::create([
                'refer_code' => $request->input('code'),
                'amount' => $request->input('amount'),
                'status' => $request->input('status'),
                'user_id' => $id,
            ]);
            return response()->json([
                'code' => 'REFER_CREATED',
                'message' => 'Refer code successfully created'
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER',
                'message' => 'Refer code couldn\'t create',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Retrieve the refer
     */
    public function show(Request $request){
        return response()->json([
            'code' => 'REFER_CODE_RETRIEVED',
            'message' => 'Refer code retrieved',
            'refer' => Refer::where('user_id', $request->header('id'))->first()
        ],200);
    }
}

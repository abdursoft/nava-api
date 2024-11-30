<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\Models\Agent;
use App\Models\PaymentTransaction;
use App\Models\Refer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Get users
     */
    public function getUser($id=null){
        if($id === null){
            $user = User::with(['paymentTransaction','refer','turnover','activeStatus','userDepositBonus','userDepositBonus.depositBonus','referHistory'])->get();
        }else{
            $user = User::with(['paymentTransaction','refer','turnover','activeStatus','userDepositBonus','userDepositBonus.depositBonus','referHistory'])->find($id);
        }
        return response()->json([
            'code' => 'USER_RETRIEVED',
            'message' => 'User successfully retrieved',
            'users' => $user
        ],200);
    }

    /**
     * Get user transaction
     */
    public function userTransaction($id=null){
        if($id === null){
            $transactions = PaymentTransaction::with(['user'])->get();
        }else{
            $transactions = PaymentTransaction::with(['user'])->find($id);
        }
        return response()->json([
            'code' => 'TRANSACTIONS_RETRIEVED',
            'message' => 'Transactions successfully retrieved',
            'transactions' => $transactions
        ],200);
    }

    /**
     * User refer
     */
    public function userRefer($id=null){
        if($id === null){
            $refer = Refer::with(['user'])->get();
        }else{
            $refer = Refer::with(['user'])->find($id);
        }
        return response()->json([
            'code' => 'REFER_RETRIEVED',
            'message' => 'Refer successfully retrieved',
            'refers' => $refer
        ],200);
    }

    /**
     * update user status
     */
    public function userStatus(Request $request){
        $validator = Validator::make($request->all(),[
            'status' => 'required',
            'id' => 'required|exists:users,id'
        ]);
        
        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATE',
                'message' => 'Invalid data',
                'errors' => $validator->errors()
            ],400);
        }

        try {
            User::where('id', $request->header('id'))->update([
                'is_verified' => $request->status
            ]);
            return response()->json([
                'code' => 'USER_UPDATED',
                'message' => 'User status successfully changed'
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage()
            ],400);
        }
    }
}

// @jvfM$)9_GvSRx7

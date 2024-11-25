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
     * Withdrawal request for the user
     */
    public function withdraw(Request $request){
        $user = Auth::guard('user')->user();

        if($request->input('amount') > $user->balance){
            return response()->json([
                'status' => false,
                'message' => 'Insufficient Balance'
            ],400);
        }

        try {
            DB::beginTransaction();
            User::where('id',$user->id)->decrement('balance',$request->input('amount'));
            Transaction::create([
                'user_id' => $user->id,
                'agent_id' => $user->agent_id,
                'amount' => $request->input('amount'),
                'intent' => 'Withdraw',
                'pay_intent' => 'Credit',
                'host_role' => 'user',
                'client_role' => 'agent',
                'status' => 'Pending',
                'end_date' => date('Y-m-d',strtotime("+3 days"))
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => "Withdrawal request has been sent to agent"
            ],201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Withdrawl request have been failed',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    // Use Details
    public function details(){
        return response()->json(Auth::guard('user-api')->user());
    }


    // will return user transaction
    public function transactions(Request $request, $slug=null){
        if(!empty($slug)){

        }else{
            return Transaction::where('user_id',$request->header('id'))->orWhere('user_id',$request->header('id'))->get();
        }
    }

    /**
     * user Profile information
     */
    public function profile(Request $request){
        $profile = User::find($request->header('id'));
        $deposit = PaymentTransaction::where('user_id',$request->header('id'))->where('pay_intent','CREDIT')->get();
        $withdraw = PaymentTransaction::where('user_id',$request->header('id'))->where('pay_intent', 'DEBIT');
        $refer = Refer::where('user_id',$request->header('id'))->get();

        return response()->json([
            "user" => $profile,
            "deposit" => $deposit,
            "withdraw" => $withdraw,
            "refer" => $refer
        ],200);
    }
}

// @jvfM$)9_GvSRx7

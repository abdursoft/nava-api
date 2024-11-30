<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DepositBonus;
use App\Models\PaymentTransaction;
use App\Models\Refer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Create new Agent
     */
    public function createAgent(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone',
            'user_name' => 'required|unique:users,user_name',
            'password' => 'required|min:4',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Couldn\'t create agent',
                'errors' => $validate->errors()
            ], 400);
        }

        try {
            DB::beginTransaction();
            $man = User::create([
                'phone' => $request->input('phone'),
                'password' => bcrypt($request->input('password')),
                'user_name' => $request->input('user_name'),
                'balance' => $request->input('balance'),
                'email' => $request->input('email') ?? '',
                'currency' => $request->input('currency') ?? 'BDT',
                'country' => $request->input('country') ?? 'BD',
                'city' => $request->input('city') ?? '',
                'street' => $request->input('street') ?? '',
                'role' => 'agent'
            ]);

            if(!empty($request->input('balance'))){
                PaymentTransaction::create([
                    'amount' => $request->input('balance') ?? 0,
                    'intent' => 'Deposit',
                    'pay_intent' => 'CREDIT',
                    'host_role' => 'agent',
                    'client_role' => 'admin',
                    'user_id' => $man->id,
                    'status' => 'Completed'
                ]);
            }
            DB::commit();
            return response()->json([
                'code' => 'SUCCESS',
                'message' => 'Agent successfully created',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Couldn\'t create Agent',
                'errors' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Site statistics
     */
    public function statistics(){
        $user = User::where('role','user')->count();
        $agent = User::where('role','agent')->count();
        $deposit = PaymentTransaction::where('pay_intent','CREDIT')->sum('payable_amount');
        $withdraw = PaymentTransaction::where('pay_intent','DEBIT')->sum('payable_amount');
        $refer = Refer::where('status', 'active')->count();
        $bonus = DepositBonus::where('status','active')->count();
        $category = Category::where('code','!=',null)->count();

        return response()->json([
            'code' => 'SITE_STATICS',
            'message' => 'Site statistics retrieved',
            'users' => $user,
            'bonus' => $bonus,
            'refer' => $refer,
            'agents' => $agent,
            'category' => $category,
            'total_deposit' => $deposit,
            'total_withdraw' => $withdraw,
        ],200);
    }
}

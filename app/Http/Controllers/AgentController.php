<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /***
     * Create new user
     */
    public function createUser(Request $request){
        $validate = Validator::make($request->all(), [
            'phone' => 'required|unique:users,phone',
            'user_name' => 'required|unique:users,user_name',
            'password' => 'required|min:4',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => 'Couldn\'t create user',
                'errors' => $validate->errors()
            ], 400);
        }


        try {
            DB::beginTransaction();

            $agent =  json_decode($request->header('agent'));
            if(!empty($request->input('balance'))){
                if($request->input('balance') <= $agent->balance){
                    User::where('id',$agent->id)->decrement('balance',$request->input('balance'));
                }else{
                    return response()->json([
                        'code' => 'INSUFFICIENT_BALANCE',
                        'message' => 'Insufficient Balance',
                    ], 400);
                }
            }

            $user = User::create([
                'phone' => $request->input('phone'),
                'password' => bcrypt($request->input('password')),
                'user_name' => $request->input('user_name'),
                'balance' => $request->input('balance') ?? 0,
                'email' => $request->input('email') ?? '',
                'currency' => $request->input('currency') ?? 'bdt',
                'country' => $request->input('country') ?? '',
                'city' => $request->input('city') ?? '',
                'street' => $request->input('street') ?? '',
                'agent_id' => $agent->id,
                'role' => 'user'
            ]);

            if(!empty($request->input('balance'))){
                Transaction::create([
                    'user_id' => $user->id,
                    'agent_id' => $agent->id,
                    'amount' => $request->input('balance') ?? 0,
                    'intent' => 'Deposit',
                    'pay_intent' => 'Debit',
                    'host_role' => 'user',
                    'client_role' => 'agent',
                    'status' => 'Completed'
                ]);
            }

            DB::commit();
            return response()->json([
                'code' => 'USER_CREATED',
                'message' => 'User successfully created',
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Couldn\'t create agent',
                'errors' => $th->getMessage(),
            ], 400);
        }
    }

    /**
     * Return active agents
     */
    public function show($type= null){
        try {
            if($type !== null){
                $agent = User::where('agent_type',$type)->where('is_deleted','0')->where('is_blocked','0')->where('role','agent')->select('user_name','country','currency','city','street','profile','is_verified','agent_type')->first();
            }else{
                $agent = User::where('is_deleted','0')->where('is_blocked','0')->where('role','agent')->select('user_name','country','currency','city','street','profile','is_verified','agent_type')->get();
            }
            return response()->json([
                'code' => 'AGENT_RETRIEVED',
                'message' => 'Agent list successfully retrieved',
                'agents' => $agent
            ],200);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => 'Agent list couldn\'t retrieve',
                'errors' => $th->getMessage()
            ],400);
        }
    }

    /**
     * Agent deposit
     */
    public function agentDeposit(Request $request){

    }

    /**
     * Admin get agents
     */
    public function adminAgents(){
        return response()->json([
            'code' => 'AGENT_RETRIEVED',
            'agents' => User::where('role','agent')->get(),
            'message' => 'Agent list successfully retrieved'
        ],200);
    }

}

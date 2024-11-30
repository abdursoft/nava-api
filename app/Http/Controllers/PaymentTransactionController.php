<?php

namespace App\Http\Controllers;

use App\Models\DepositBonus;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\UserTurnOver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PaymentTransactionController extends Controller
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
                'code' => 'TRANSACTION_RETRIEVED',
                'message' => 'User transaction successfully retrieved',
                'transactions' => PaymentTransaction::where('user_id',$request->header('id'))->get()
            ],200);
        }else{
            return response()->json([
                'code' => 'TRANSACTION_RETRIEVED',
                'message' => 'User transaction successfully retrieved',
                'transactions' => PaymentTransaction::where('user_id',$request->header('id'))->where('id', $id)->first()
            ],200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentTransaction $paymentTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentTransaction $paymentTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentTransaction $paymentTransaction)
    {
        //
    }


    /**
     * Deposit
     */
    public function deposit(Request $request){
        $validator = Validator::make($request->all(),[
            'amount' => 'required',
            'payment' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => "Payment invoice couldn't create",
                "errors" => $validator->errors()
            ],400);
        }
        $amount = 0;
        if(!empty($request->input('bonus_id'))){
            $bonus = DepositBonus::where('id',$request->input('bonus_id'))->where('status','active')->first();
            if($bonus){
                if($request->input('amount') >= $bonus->minimum){
                    Session::put(['bonusId' => $bonus->id]);
                    $amount = $bonus->amount * $request->input('amount') / 100;
                }
            }
        }else{
            $amount = $request->amount;
        }

        Session::put(['amount' => $amount]);

        try {
            $user = User::find($request->header('id'));
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'intent' => 'Payment',
                'pay_intent' => 'CREDIT',
                'bonus_id' => $request->bonus_id,
                'payment' => strtolower($request->payment),
                'payable_amount' => $request->amount
            ]);

            return response()->json([
                'code' => 'INVOICE_CREATED',
                'url' => $request->root()."/payment/".$transaction->id,
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage()
            ],400);
        }
    }

    /**
     * user withdraw
     */
    public function withdraw(Request $request){
        $validator = Validator::make($request->all(),[
            'amount' => 'required',
            'payment' => 'required',
            'phone' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'code' => 'INVALID_DATA',
                'message' => "Payment invoice couldn't create",
                "errors" => $validator->errors()
            ],400);
        }
        $amount = $request->amount;

        $turnover = UserTurnOver::where('user_id',$request->header('id'))->where('status','Running')->first();
        if($turnover && $turnover->amount > $amount){
            return response()->json([
                'code' => 'TURNOVER_HIGH',
                'message' => 'Withdrawal amount less than turnover amount',
            ],400);
        }
        Session::put(['amount' => $amount]);
        $turnover ? Session::put(['turnover' => $turnover->id]) : true;

        try {
            $user = User::find($request->header('id'));
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'intent' => 'Withdraw',
                'pay_intent' => 'DEBIT',
                'payment' => strtolower($request->payment),
                'payable_amount' => $request->amount
            ]);

            return response()->json([
                'code' => 'INVOICE_CREATED',
                'url' => $request->root()."/payout/".$transaction->id,
            ],201);
        } catch (\Throwable $th) {
            return response()->json([
                'code' => 'INTERNAL_SERVER_ERROR',
                'message' => $th->getMessage()
            ],400);
        }
    }
}

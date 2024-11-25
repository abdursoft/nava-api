<?php

namespace App\Http\Controllers\Payment;

use Abdursoft\LaravelBkash\Bkash;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\User;
use App\Models\UserDepositBonus;
use App\Models\UserTurnOver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class BkashController extends Controller
{
    protected $bkash;
    public function __construct()
    {
        $this->bkash = new Bkash(env('BKASH_USERNAME'),env('BKASH_PASSWORD'),env('BKASH_APP_KEY'),env('BKASH_APP_SECRET'),'production',"https://api.nava99.pro/payment/callback");
    }

    /**
     * Create payment
     */
    public function createPayment($amount, $token,){
        $payment = $this->bkash->paymentCreate('store_payment',$amount,$token);
        return redirect()->away($payment['bkashURL']);
    }

    /**
     * Refund payment
     */
    public function refundPayment(Request $request){
        $refund = $this->bkash->refund($request->id,$request->txn,$request->amount,$request->sku,$request->reason);
        if($refund['statusCode'] === '0000'){
            return redirect('/')->with('success',"Refund process has been completed");
        }else{
            return redirect('/')->with('error',"Refund process has been failed");
        }
    }

    /**
     * Cancel status
     */
    public function cancel(Request $request){
        return $request->all();
    }


    /**
     * Bkash callback
     */
    public function callbackResponse(Request $request){
        if($request->query('status') == 'success'){
            $execute = $this->bkash->paymentExecute($request->query('paymentID'));
            if($execute['statusCode'] == '0000'){
                $invoice = PaymentTransaction::find($execute['merchantInvoiceNumber']);
                if($invoice){
                    $user = User::find($invoice->user_id);
                    $user->increment('balance',$execute['amount']);
                    PaymentTransaction::create([
                        'user_id' => $user->id,
                        'amount' => Session::get('amount') ?? $execute['amount'],
                        'intent' => 'DEPOSIT',
                        'status' => 'Completed',
                        'host_role' => 'user',
                        'wallet' => $execute['customerMsisdn'] ?? Null,
                        'pay_intent' => 'CREDIT',
                    ]);

                    if(Session::get('bonusId')){
                        UserDepositBonus::create([
                            'user_id' => $user->id,
                            'bonus_id' => Session::get('bonus_id')
                        ]);
                    }
                }
                return redirect('/')->with('success','Payment has been completed');
            }elseif($execute['statusCode'] == '2062'){
                return redirect('/')->with('success','Payment has already been completed');
            }
        }elseif($request->query('status') == 'cancel'){
            return redirect('/')->with('error','Payment has been canceled');
        }elseif($request->query('status') == 'failure'){
            return redirect('/')->with('error','Payment has been failed');
        }else{
            return redirect('/')->with('error','Payment couldn\'t completed');
        }
    }

    /**
     * Payout with Bkash
     */
    public function payout($amount,$invoice, $phone){
        try {
            $payment = $this->bkash->payout($amount,$invoice, $phone);
            if($payment['statusCode'] == '0000'){
                $invoice = PaymentTransaction::find($payment['merchantInvoiceNumber']);
                if($invoice){
                    $user = User::find($invoice->user_id);
                    $user->increment('balance',$payment['amount']);
                    PaymentTransaction::create([
                        'user_id' => $user->id,
                        'amount' => Session::get('amount') ?? $payment['amount'],
                        'intent' => 'WITHDRAW',
                        'status' => 'Completed',
                        'host_role' => 'user',
                        'wallet' => $phone,
                        'pay_intent' => 'DEBIT',
                    ]);

                    if(Session::get('turnover')){
                        UserTurnOver::where('id', Session::get('turnover'))->update([
                            'status' => 'Completed'
                        ]);
                    }
                    if(Session::get('bonusId')){
                        UserDepositBonus::create([
                            'user_id' => $user->id,
                            'bonus_id' => Session::get('bonus_id')
                        ]);
                    }
                }
                return redirect()->away(env('FRONT_END'));
            }
        } catch (\Throwable $th) {
            return redirect('/')->with('error',$th->getMessage());
        }
    }
}

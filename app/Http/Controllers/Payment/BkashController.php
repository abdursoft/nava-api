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
        $this->bkash = new Bkash(env('BKASH_USERNAME'),env('BKASH_PASSWORD'),env('BKASH_APP_KEY'),env('BKASH_APP_SECRET'),'production',"https://api.nava99.pro/payment/bkash/callback");
    }

    /**
     * Create payment
     */
    public function createPayment($amount, $token){
        return $this->bkash->paymentCreate('store_payment',$amount,$token);
    }

    /**
     * Refund payment
     */
    public function refundPayment(Request $request){
        $refund = $this->bkash->refund($request->id,$request->txn,$request->amount,$request->sku,$request->reason);
        if($refund['statusCode'] === '0000'){
            return redirect()->away(env('PAYMENT_END')."?success=Refund process has been completed");
        }else{
            return redirect()->away(env('PAYMENT_END')."?error=Refund process has been failed");
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
                    $invoice->update([
                        'status' => "Completed"
                    ]);

                    if(Session::get('bonusId')){
                        UserDepositBonus::create([
                            'user_id' => $user->id,
                            'bonus_id' => Session::get('bonus_id')
                        ]);
                    }
                }
                return redirect()->away(env('PAYMENT_END')."?success=Payment has been completed");
            }elseif($execute['statusCode'] == '2062'){
                $this->changeStatus($request->query('paymentID'));
                return redirect()->away(env('PAYMENT_END')."?success=Payment has already been completed");
            }
        }elseif($request->query('status') == 'cancel'){
            $this->changeStatus($request->query('paymentID'));
            return redirect()->away(env('PAYMENT_END')."?error=Payment has been canceled");
        }elseif($request->query('status') == 'failure'){
            $this->changeStatus($request->query('paymentID'));
            return redirect()->away(env('PAYMENT_END')."?error=Payment has been failed");
        }else{
            $this->changeStatus($request->query('paymentID'));
            return redirect()->away(env('PAYMENT_END')."?error=Payment couldn't completed");
        }
    }

    /***
     * Change payment status
     */
    public function changeStatus($id){
        $invoice = PaymentTransaction::where('payment_id',$id)->first();
        if($invoice){
            $invoice->update(['status' => 'Failed']);
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
                    $invoice->update([
                        'status' => "Completed"
                    ]);

                    if(Session::get('turnover')){
                        UserTurnOver::where('id', Session::get('turnover'))->update([
                            'status' => 'Completed'
                        ]);
                    }
                }
                return redirect()->away(env('PAYMENT_END'));
            }
        } catch (\Throwable $th) {
            return redirect()->away(env('PAYMENT_END'));
        }
    }
}

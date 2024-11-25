<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Deposit payment
     */
    public function payment($id){
        try {
            $payment = PaymentTransaction::find($id);
            if($payment->payment === 'bkash'){
                $bkash = new BkashController();
                return $bkash->createPayment($payment->amount,$id);
            }
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }

    /**
     * Withdraw payment
     */
    public function withdraw($id){
        try {
            $payment = PaymentTransaction::find($id);
            if($payment->payment === 'bkash'){
                $bkash = new BkashController();
                return $bkash->payout($payment->amount,$id,$payment->wallet);
            }
        } catch (\Throwable $th) {
            return redirect('/');
        }
    }
}

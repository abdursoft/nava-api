<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;

class PaymentController extends Controller
{
    /**
     * Deposit payment
     */
    public function payment($id)
    {
        try {
            $payment = PaymentTransaction::find($id);
            if ($payment->status === 'Pending' && $payment->pay_intent === 'CREDIT') {
                if ($payment->payment === 'bkash') {
                    $bkash = new BkashController();
                    $bkashPayment = $bkash->createPayment($payment->payable_amount, $id);
                    $payment->update(['payment_id' => $bkashPayment['paymentID']]);
                    return redirect()->away($bkashPayment['bkashURL']);
                }
            } else {
                return redirect()->away(env('FRONT_END'));
            }
        } catch (\Throwable $th) {
            return redirect()->away(env('FRONT_END'));
        }
    }

    /**
     * Withdraw payment
     */
    public function withdraw($id)
    {
        try {
            $payment = PaymentTransaction::find($id);
            if ($payment->status === 'Pending' && $payment->pay_intent === 'CREDIT') {
                if ($payment->payment === 'bkash') {
                    $bkash = new BkashController();
                    return $bkash->payout($payment->payable_amount, $id, $payment->wallet);
                }
            } else {
                return redirect()->away(env('FRONT_END'));
            }
        } catch (\Throwable $th) {
            return redirect()->away(env('FRONT_END'));
        }
    }
}

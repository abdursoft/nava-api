<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\JWTAuth;
use App\Mail\OtpMail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    /**
     * Show the forgot email form view
     */
    public function otpSend(){
        // return view('components.password.send-otp');
    }

    /**
     * Otp verify for reset password
     */
    public function otpVerifyView(){
        // return view('components.password.otp-verify');
    }

    /**
     * New password view
     */
    public function newPassword(){
        // return view('components.password.password');
    }


    /**
     * sending the new otp for reset password
     */
    public function sendOTP(Request $request){
        if(!empty($request->input('email'))){
            try {
                $user = User::where('email',$request->input('email'))->first();
                $otp = rand(1000,9999);
                $otpToken = JWTAuth::createToken('password_otp',.5,null,$request->input('email'));
                $user->update([
                    'otp' => $otp
                ]);
                // Mail::to($request->email)->send(new OtpMail($otp));
                return response()->json([
                    'code' => 'OTP_SENT',
                    'message' => 'OTP has been successfully sent',
                    'token' => $otpToken
                ]);
            } catch (\Throwable $th) {
                return response()->json([
                    'code' => 'UNREGISTER_MAIL',
                    'message' => 'This email is not registered'
                ],400);
            }
        }else{
            return response()->json([
                'code' => 'EMAIL_REQUIRED',
                'message' => 'Email is a required field'
            ],400);
        }
    }

    /**
     * Verify otp and reset password for token
     */
    public function verifyOTP(Request $request){
        if(!empty($request->input('otp'))){
            try {
                $token = JWTAuth::verifyToken($request->input('otp_token'),false);
                $passToken = JWTAuth::createToken('password_token',.5,null,$token->email);
                $user = User::where('email',$token->email)->first();
                if( $request->input('otp') == $user->otp){
                    User::where('id', $user->id)->update(['otp' => '']);
                    return response()->json([
                        'code' => 'OTP_SENT',
                        'message' => 'OTP match, Go for next',
                        "password_token" => $passToken
                    ],200);
                }else{
                    return response()->json([
                        'code' => 'INVALID_OTP',
                        'message' => 'Invalid OTP',
                    ],400);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'code' => 'OTP_EXPIRED',
                    'message' => 'Your OTP token was expired',
                    'error' => $th->getMessage(),
                ],400);
            }
        }else{
            return response()->json([
                'code' => 'INVALID_OTP',
                'message' => 'Invalid OTP is required'
            ],400);
        }
    }

    /**
     * Changing the new password
     */
    public function passwordReset(Request $request){
        $old = $request->input('old_password');
        $new = $request->input('new_password');
        $con = $request->input('confirm_password');

        if($new == $con){
            if(strlen($new) < 5){
                return response()->json([
                    'code' => 'LENGTH_MORE_5',
                    'message' => 'Password length must be 5 or more characters'
                ],400);
            }else{
                try {
                    $user = User::find('email',$request->header('id'));
                    if(password_verify($old,$user->password)){
                        $user->update([
                            'password' => password_hash($new,PASSWORD_DEFAULT)
                        ]);
                        return response()->json([
                            'code' => 'PASSWORD_RESET_SUCCESS',
                            'message' => "Password successfully reset"
                        ],200);
                    }else{
                        return response()->json([
                            'code' => 'OLD_PASSWORD_NOT_MATCH',
                            'message' => 'Old password not match!'
                        ],400);
                    }
                } catch (\Throwable $th) {
                    return response()->json([
                        'code' => 'INTERNAL_SERVER_ERROR',
                        'message' => 'Authorization or Server Error!'
                    ],400);
                }
            }
        }else{
            return response()->json([
                'code' => 'BOTH_PASSWORD_SAME',
                'message' => 'Both password must be same'
            ],400);
        }
    }
}

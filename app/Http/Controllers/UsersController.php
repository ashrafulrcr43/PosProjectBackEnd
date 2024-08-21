<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use App\Helper\JWTToken;
use Illuminate\Support\Facades\Hash;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use Exception;

class UsersController extends Controller
{
    public static function registration(Request $request){
        user::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'mobile' => $request->mobile,
            // 'password' => Hash::make($request->password)
            'password' => $request->password


        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'user created successfully'


        ], 201);
    }
    
    public static function loginUser(Request $request){
        $count=User::where('email', $request->email)
        ->where('password', $request->password)
        ->count();


        if($count==1){
            $token = JWTToken::createToken($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'user logged in successfully',
                'token' => $token
            ]);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'user not found'
            ]);
        }
    }
    public function sendOTP(Request $request){
        $email = $request->input('email');
        $otp=rand(100000,999999);
        $count = User::where('email','=',$email)->count();
       
        if($count == 1){
          Mail::to($email)->send(new OTPMail($otp));
          User::where('email','=',$email)->update(['otp' => $otp]);
          return response()->json([
              'status' => 'success',
              'message' => 'OTP sent successfully',
          ]);
         
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'user not found'
            ]);
        }
    }

    public function verifyOtp(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email','=', $email)->where('otp','=', $otp)->count();

        if($count == 1){
            User::where('email','=', $email)->update(['otp' => '0']);
            $token = JWTToken::createTokenSetPassword($email);
            return response()->json([
                'status' => 'success',
                'message' => 'Otp verified  successfully',
                'token' => $token
            ]);
        }else{
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid OTP',
            ]);
        }
    }
    public function resetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');
              
            User::where('email','=', $email)->update(['password' => $password]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'password reset successfully'
                ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 'failed',
                'message' => 'user not found'
            ]);
        }
       
        
    }



}

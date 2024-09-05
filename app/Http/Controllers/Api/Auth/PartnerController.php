<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseApiController as ApiBaseApiController;
use App\Http\Requests\LoginRoleRequest;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Mail\SendOtpMail;
use App\Models\Partner;
use App\Models\verifyOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PartnerController extends ApiBaseApiController
{
    public function register(RoleRequest $request)
    {

        $user = Partner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $otp = rand(100000, 999999);
        $expiry_duration = 5 * 60;
        $expiry_time = date('Y-m-d H:i:s', time() + $expiry_duration);

        $user = verifyOtp::create([
            'code' => $otp,
            'email' => $request->email,
            'role' => 'partner',
            'expired_date' => $expiry_time,
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return $this->generateResponse(true,'Registration success and OTP sent to your email. Please verify.');
    }

    public function login(LoginRoleRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->generateResponse(true,'Invalid login credentials',[],401);
        }

        $otp = rand(100000, 999999);
        $expiry_duration = 5 * 60;
        $expiry_time = date('Y-m-d H:i:s', time() + $expiry_duration);

        $user = verifyOtp::create([
            'code' => $otp,
            'email' => $request->email,
            'role' => 'partner',
            'expired_date' => $expiry_time,
        ]);

        // Send OTP via email
        Mail::to($user->email)->send(new SendOtpMail($otp));

        return $this->generateResponse(true,'OTP sent to your email. Please verify.');
    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
        $verifyOtp = verifyOtp::where('role',$request->role)->where('email',$request->email)->orderBy('id', 'DESC')->first();
        if ($request->otp == $verifyOtp->code) {
            $current_time = date('Y-m-d H:i:s');
            if($current_time <= $verifyOtp->expired_date){
                $credentials = $request->only('email', 'password');
                if (Auth::guard('partner')->attempt($credentials)) {
                    $user = Auth::guard('partner')->user();
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return $this->generateResponse(true,'success',['access_token' => $token, 'token_type' => 'Bearer']);
                }else{
                    return $this->generateResponse(true,'Invalid credentials',[],401);
                }
            }else{
                $otp = rand(100000, 999999);
                $expiry_duration = 5 * 60;
                $expiry_time = date('Y-m-d H:i:s', time() + $expiry_duration);

                $user = verifyOtp::create([
                    'code' => $otp,
                    'email' => $request->email,
                    'role' => 'partner',
                    'expired_date' => $expiry_time,
                ]);

                Mail::to($user->email)->send(new SendOtpMail($otp));
                return $this->generateResponse(true,'OTP expired , we resend OTP in email',[],401);
            }
        }

        return $this->generateResponse(true,'Invalid OTP',[],401);

    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return $this->generateResponse(true,'Successfully logged out');
    }

    public function user(Request $request)
    {
        return $this->generateResponse(true,'success',[$request->user()]);
    }
}

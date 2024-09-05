<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController as ApiBaseApiController;
use App\Http\Requests\RestPasswordRequest;
use App\Models\Company;
use App\Models\Partner;
use App\Models\passwordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class PasswordResetController extends ApiBaseApiController
{
    public function RestPasswordRequest(RestPasswordRequest $request)
    {
        if($request->role == 'user'){
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
        }else if($request->role == 'company'){
            $user = Company::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
        }else if($request->role == 'partner'){
            $user = Partner::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();
        }else{
            return $this->generateResponse(false,'Invalid credentials',[]);
        }

        return $this->generateResponse(true,'Rest password is success .');

    }

}

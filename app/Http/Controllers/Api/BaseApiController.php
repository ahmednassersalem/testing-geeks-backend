<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    protected function generateResponse(bool $isSuccess = true, string $message = '',$data = [], $statusCode = 200, array $headers = [])
    {
        if($isSuccess == false){
            $response = [
                'success' => $isSuccess,
                'error' => $message,
                'data' => $data
            ];
        }else{
            $response = [
                'success' => $isSuccess,
                'message' => $message,
                'data' => $data
            ];
        }
        return response()->json($response, $statusCode, $headers);
    }
}

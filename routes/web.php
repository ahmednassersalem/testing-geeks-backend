<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/unauthorized', function () {
    $response = [
        'message' => 'Unauthorized',
        'status' => false,
    ];
    return response()->json($response, 401);
})->name('login');

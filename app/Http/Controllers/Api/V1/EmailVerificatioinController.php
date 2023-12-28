<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificatioinController extends Controller
{
    function notice () {
        return response()->json([
            "redirectUrl" => "/verify-email",
            "message" => "Please verify your password"
        ]);
    }

    function verify (EmailVerificationRequest $request) {
        $request->fulfill();
    
        return response()->json(['message' => 'Email verified']);
    }

    function resend (Request $request) {
        $request->user()->sendEmailVerificationNotification();
    
        return response()->json(['message' => 'Verification link sent!']);
    }
}

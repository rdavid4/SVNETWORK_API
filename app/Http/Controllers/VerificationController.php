<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class VerificationController extends Controller
{
    public function verifyEmail(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $userId = $request->input('user');
        $user = User::findOrFail($userId);

        if($user->email_verified_at !== null){
            abort(422,'Este email ya ha sido verificado');
        }

        $user->markEmailAsVerified();
        $user->save();

        return $user;
    }
}

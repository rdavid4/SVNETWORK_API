<?php

namespace App\Http\Controllers;

use App\Models\VerificationCode;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use App\Models\User;
class VerificationCodeController extends Controller
{
    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendSmsVerification(Request $request)
    {
        $request->validate([
            'phone_number' => 'required'
        ]);

        $verifiedPhone = VerificationCode::where('phone_number', $request->phone_number)->where('is_verified', true)->first();

        $phoneNumber = $request->input('phone_number');
        $verificationCode = rand(100000, 999999); // Generar un código de 6 dígitos

        VerificationCode::create([
            'code' => $verificationCode,
            'phone_number' => $phoneNumber
        ]);
        // Envía el SMS con Twilio
        $this->twilioService->sendVerificationCode($phoneNumber, $verificationCode);

        return response()->json(['message' => 'Verification code sent successfully!'], 201);

    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'code' => 'required',
        ]);

        $user = User::find($request->user_id);
       $verificationCodes = VerificationCode::where('phone_number', $request->phone_number)->get();
       $codeExist = $verificationCodes->where('code', $request->code);

        if($codeExist->count() == 1){

            $code = $codeExist->first();
            $code->is_verified = true;
            $code->save();
            $user->verified_phone = true;
            $user->save();
            return response()->json(['message' => 'Verification successfully!']);
        }else{
            VerificationCode::where('phone_number', $request->phone_number)->delete();
            abort(422, 'Wrong code');
        }
    }


}

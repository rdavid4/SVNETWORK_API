<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Http\Resources\UserResource;
class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, fn (Builder $query) => $query->where('email_verified_at','!=', null)])) {
            return auth()->user();
        }

        return abort(422,'The provided credentials do not match our records.');
    }

    public function logout(Request $request)
    {

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(["message" => "Sesión cerrada correctamente"], 201);
    }
}
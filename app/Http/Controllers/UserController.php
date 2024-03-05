<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardUserResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserController extends Controller
{
    public function list(){
        $users = User::where('is_admin', 0)->orderBy('id', 'DESC')->get();
        return DashboardUserResource::collection($users);
    }
    public function show(){
        $user = auth()->user();
        return new UserResource($user);
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'email_verified_at' => now(),
            'pro' => 1
        ]);

        return new UserResource($user);
    }
    public function updatePassword(Request $request){
        $request->validate([
            'password' => 'required'
        ]);

        $email = auth()->user()->email;
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    public function storeImage(User $user, Request $request){

        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $user->id.'/image-'.uniqid() . '.' . $image->extension();;
            Storage::disk('users')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('users')->url($nombreArchivo);
            $user->image = $urlArchivo;
        }

        $user->save();

        return $user;

    }

    function emailExist($email)
    {
        $exist = User::where("email",$email)->whereNotNull('password')->exists();
        return $exist;
    }
    function AdminEmailExist($email)
    {
        $exist = User::where("email",$email)->exists();
        return $exist;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\DashboardCompanyResource;
use App\Http\Resources\DashboardUserResource;
use App\Http\Resources\UserCompanyResource;
use App\Http\Resources\UserProjectResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Project;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function list()
    {
        $users = User::where('is_admin', 0)->orderBy('updated_at', 'DESC')->get();
        return DashboardUserResource::collection($users);
    }
    public function listPro()
    {
        $users = User::where('pro', 1)->orderBy('updated_at', 'DESC')->get();
        return DashboardUserResource::collection($users);
    }
    public function show()
    {
        $user = auth()->user();
        return new UserResource($user);
    }
    public function showProject(Project $project)
    {
        return new UserProjectResource($project);
    }
    public function company()
    {
        $user = auth()->user();

        $company = $user->companies->first();
        if($company){
            return new UserCompanyResource($company);
        }else{
        return abort(404,'Company not found');
        }


    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required'
        ]);

        $user = auth()->user();
        $user->name =  $request->name;
        $user->surname =  $request->surname;

        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();
        return new UserResource($user);
    }
    public function store(Request $request)
    {
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
    public function storeGuess(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'email' => 'required',
            'phone' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'phone' => $request->phone
            ]);
        }

        Auth::login($user);


        return new UserResource($user);
    }
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $email = auth()->user()->email;
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return $user;
    }

    public function storeImage(User $user, Request $request)
    {

        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $user->id . '/image-' . uniqid() . '.' . $image->extension();;
            Storage::disk('users')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('users')->url($nombreArchivo);
            $user->image = $urlArchivo;
        }

        $user->save();

        return $user;
    }
    public function storeImageAuthUser(Request $request)
    {

        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'
        $user = auth()->user();
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $user->id . '/image-' . uniqid() . '.' . $image->extension();;
            Storage::disk('users')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('users')->url($nombreArchivo);
            $user->image = $urlArchivo;
        }

        $user->save();

        return $user;
    }
    public function projects(User $user)
    {
        $user = User::first();
        return UserProjectResource::collection($user->projects);
    }

    function emailExist($email)
    {
        $exist = User::where("email", $email)->whereNotNull('password')->exists();
        return $exist;
    }
    function AdminEmailExist($email)
    {
        $exist = User::where("email", $email)->exists();
        return $exist;
    }
    function checkRobot(Request $request)
    {
        $client = new Client(); // Instancia de Guzzle
        $secret = '6Ldhxr4pAAAAABRZA1oXE9I3SgHS9syhmt9NpHs3'; // Clave secreta de reCAPTCHA
        $response = $request->input('response'); // Respuesta de reCAPTCHA



        $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify', [ 'form_params'=>['secret' => $secret, 'response' => $response]]);

        $body = $response->getBody();
        $data = json_decode($body, true);

       return $data;
    }
}

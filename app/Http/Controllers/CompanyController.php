<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyConfigurationResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyServiceResource;
use App\Http\Resources\DashboardCompanyResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\UserCompanyResource;
use App\Models\Company;
use App\Models\CompanyServiceZip;
use App\Models\Image;
use App\Models\Mautic;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use App\Models\Zipcode;
use App\Notifications\CompanyCreatedNotification;
use App\Notifications\CompanyVerifiedNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function list()
    {
        $companies = Company::orderBy('updated_at', 'desc')->get();
        return DashboardCompanyResource::collection($companies);
    }
    public function listUnverified()
    {
        $companies = Company::where('verified', 0)->get();
        return CompanyResource::collection($companies);
    }

    public function show(Company $company)
    {
        return new DashboardCompanyResource($company);
    }
    public function projects(Company $company)
    {
        $project = $company->projects()->paginate();
        return $project;
    }
    public function verify(Request $request)
    {
        $request->validate([
            'company_id' => 'required'
        ]);

        $company = Company::findOrFail($request->company_id);
        $company->verified = 1;
        $company->save();
        $users = $company->users;
        if ($users) {
            foreach ($users as $key => $user) {
                $user->link = config('app.app_url') . '/user/companies/profile';
                $user->link2 = config('app.app_url') . '/legal/pro-terms';
                $user->notify(new CompanyVerifiedNotification($user));
            }
        }
        return new CompanyResource($company);
    }
    public function addUser(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'user_id' => 'required',
        ]);
        $user = User::find($request->user_id);
        $user->pro = true;
        $company = Company::findOrFail($request->company_id);
        $company->users()->syncWithoutDetaching($request->user_id);
        $user->save();
        return new CompanyResource($company);
    }
    public function showbySlug($slug)
    {
        $company = Company::where('slug', $slug)->firstOrFail();
        return new CompanyResource($company);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'state' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        $company = Company::create([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'city' => $request->city,
            'state' => $request->state_id,
            'zip_code' => $request->zip_code
        ]);


        if ($request->filled('state')) {
            $company->state_id = $request->state["id"];
        }

        if ($request->filled('services')) {
            foreach ($request->services as $key => $service) {
                $company->services()->syncWithoutDetaching($service["id"]);
            }
        }
        if ($request->filled('categories')) {
            foreach ($request->categories as $key => $category) {
                $company->categories()->syncWithoutDetaching($category["id"]);
            }
        }
        if ($request->filled('phone_2')) {
            $company->phone_2 = $request->phone_2;
        }
        if ($request->filled('phone')) {
            $company->phone = $request->phone;
        }

        if ($request->filled('address_line2')) {
            $company->address_line2 = $request->address_line2;
        }

        if ($request->filled('social_facebook')) {
            $company->social_facebook = $request->social_facebook;
        }

        if ($request->filled('social_x')) {
            $company->social_x = $request->social_x;
        }

        if ($request->filled('social_youtube')) {
            $company->social_youtube = $request->social_youtube;
        }

        if ($request->filled('video_url')) {
            $company->video_url = $request->video_url;
        }

        $company->save();

        return new CompanyResource($company);
    }
    public function storeFromRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'company_name' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
            'user_id' => 'required',
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'
        $user = User::findOrfail($request->user_id);

        $company = Company::create([
            'name' => $request->company_name,
            'email' => $user->email,
            'description' => $request->description,
            'phone' => $request->phone,
            'address_line1' => $request->address_line1,
            'city' => $request->city,
            'zip_code' => $request->zip_code
        ]);

        try{
            $data = [
                'firstname' => $user->name,
                'lastname' => $user->surname,
                'email' => $user->email,
                'company' => $request->company_name,
                'phone' => $request->phone,
                'country' => $request->country,
                'city' => $request->city,
                'state' => $request->state['name_en'],
                'zipcode' => $request->zip_code,
                'tags' => 'company'
            ];
            Mautic::createContact($data);
        }catch(Exception $e){
            return $e;
        }
        if ($request->filled('state')) {
            $company->state_id = $request->state["id"];
        }

        if ($request->filled('services')) {
            foreach ($request->services as $key => $service) {
                $company->services()->syncWithoutDetaching($service["id"]);
            }
        }
        if ($request->filled('categories')) {
            foreach ($request->categories as $key => $category) {
                $company->categories()->syncWithoutDetaching($category["id"]);
            }
        }
        if ($request->filled('phone_2')) {
            $company->phone_2 = $request->phone_2;
        }
        if ($request->filled('phone')) {
            $company->phone = $request->phone;
        }

        if ($request->filled('address_line2')) {
            $company->address_line2 = $request->address_line2;
        }

        if ($request->filled('social_facebook')) {
            $company->social_facebook = $request->social_facebook;
        }

        if ($request->filled('social_x')) {
            $company->social_x = $request->social_x;
        }

        if ($request->filled('social_youtube')) {
            $company->social_youtube = $request->social_youtube;
        }

        if ($request->filled('video_url')) {
            $company->video_url = $request->video_url;
        }

        $company->save();

        $user->companies()->syncWithoutDetaching($company->id);


        $admins = User::where('is_admin', 1)->get();
        $link = config('app.app_url') . '/admin/companies';
        $company->link = $link;
        foreach ($admins as $user) {
            $user->notify(new CompanyCreatedNotification($company));
        }


        return new CompanyResource($company);
    }

    public function storeStates(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'company_id' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'
        $service = Service::find($request->service_id);
        $service->companyServiceState()->where('company_id', $request->company_id)->delete();
        if ($request->filled('states')) {
            if (isset($request->states)) {
                foreach ($request->states as $key => $state) {
                    $service->states()->attach([$state['id'] => ["company_id" => $request->company_id]]);
                }
            }
        }


        $company_id = $request->company_id;
        $states = $service->states()->where('company_id', $company_id)->get();
        $states->map(function ($state) use ($service, $company_id) {
            $state->regions = $state->regions()->map(function ($region) use ($service, $company_id, $state) {

                $serviceZipCodes = CompanyServiceZip::where('service_id', $service->id)
                    ->where('company_id', $company_id)->where('region_text', $region)
                    ->where('state_iso', $state->iso_code)
                    ->get();
                $serviceZipCodes = $serviceZipCodes->map(function ($zipcodes) {
                    return $zipcodes->zipcode;
                });


                return ["name" => $region["name"], "zipcodes" => $serviceZipCodes, "zipTotal" => count($serviceZipCodes)];
            });

            $zipcodesCount = 0;
            $zipcodesCount = collect($state->regions)->reduce(function ($sum, $region) {
                return $sum + count($region['zipcodes']);
            }, 0);

            $state->areasTotal = $zipcodesCount;
            // if($state->region->zipcodes){
            //     $state->region_count = 3;
            // }
            return $state;
        });
        $service->states = $states;
        return new CompanyServiceResource($service);
    }

    public function storeLogoAdmin(Company $company, Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $company->id . '/logo-' . uniqid() . '.' . $image->extension();;
            Storage::disk('companies')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('companies')->url($nombreArchivo);
            $company->logo_url = $urlArchivo;
        }

        $company->save();

        return $company;
    }
    public function storeLogo(Company $company, Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $company->id . '/logo-' . uniqid() . '.' . $image->extension();;
            Storage::disk('companies')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('companies')->url($nombreArchivo);
            $company->logo_url = $urlArchivo;
        }

        $company->save();

        return $company;
    }
    public function storeCover(Company $company, Request $request)
    {
        $request->validate([
            'image' => 'required'
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $nombreArchivo = $company->id . '/cover-' . uniqid() . '.' . $image->extension();;
            Storage::disk('companies')->put($nombreArchivo, file_get_contents($image));
            $urlArchivo = Storage::disk('companies')->url($nombreArchivo);
            $company->cover_url = $urlArchivo;
        }

        $company->save();

        return $company;
    }

    public function storeImages(Company $company, Request $request)
    {
        if ($request->hasFile('images')) {
            $imagenes = $request->file('images');

            foreach ($imagenes as $image) {

                if ($image->isValid()) {
                    // Realizar acciones con cada imagen, como guardarla en el servidor

                    $nombreArchivo = $company->id . '/projects/image-' . uniqid() . '.' . $image->extension();
                    Storage::disk('companies')->put($nombreArchivo, file_get_contents($image));
                    $extension = $image->extension();
                    $size = $image->getSize();
                    $mimetype = $image->getMimeType();
                    $ancho = null;
                    $alto = null;
                    $infoImagen = getimagesize($image);

                    if ($infoImagen) {
                        $ancho = $infoImagen[0]; // Ancho de la imagen
                        $alto = $infoImagen[1]; // Alto de la imagen
                    }
                   $company->images()->create([
                        'filename' => $nombreArchivo,
                        'mime_type' => $mimetype,
                        'extension' => $extension,
                        'width' => $ancho,
                        'height' => $alto,
                        'size' => $size
                    ]);
                    return "Imágenes subidas correctamente.";
                }else{
                    return 'no valido';
                }
            }

        } else {
            return "No se encontraron imágenes para subir.";
        }
    }

    public function deleteImage(Image $image){
        $disk = Storage::disk('companies');
        $image->delete();
        // Verifica si el archivo existe
        if ($disk->exists($image->filename)) {
            // Elimina el archivo
            $disk->delete($image->filename);
            return response()->json(['message' => 'Image deleted successfully.'], 200);
        } else {
            return response()->json(['message' => 'Image not found.'], 404);
        }
    }

    public function storeVideo(Company $company, Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimetypes:video/mp4,video/mpeg,video/quicktime|max:102400'
        ]);
        $video = $request->file('video');

        // Verificar si el archivo se ha cargado correctamente
        if ($video->isValid()) {
            // Generar un nombre único para el archivo de video
            $nombreArchivo = '/video-' . uniqid() . '.' . $video->getClientOriginalExtension();
            $patch = $company->id . '/' . $nombreArchivo;
            try {
                // Guardar el archivo de video en el disco 'companies'
                $video->storeAs($company->id, $nombreArchivo, 'companies');

                // Obtener la URL del archivo de video almacenado
                $urlArchivo = Storage::disk('companies')->url($patch);

                // Actualizar la URL del video en el modelo $company
                $company->video_url = $urlArchivo;

                // Guardar los cambios en la base de datos
                $company->save();

                return response()->json(['message' => 'Video guardado correctamente', 'url' => $urlArchivo]);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Error al guardar el video', 'error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['message' => 'El video no es válido'], 400);
        }
    }

    public function updateAdmin(Company $company, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        $company->name = $request->name;
        $company->email = $request->email;
        $company->description = $request->description;
        $company->phone = $request->phone;
        $company->address_line1 = $request->address_line1;
        $company->city = $request->city;
        $company->zip_code = $request->zip_code;
        $company->video_url = $request->video_url;

        if ($request->filled('states')) {
            $company->state_id = $request->state["id"];
        }

        if ($request->filled('services')) {
            foreach ($request->services as $key => $service) {
                $company->services()->syncWithoutDetaching($service["id"]);
            }
        }
        if ($request->filled('categories')) {
            foreach ($request->categories as $key => $category) {
                $company->categories()->syncWithoutDetaching($category["id"]);
            }
        }
        if ($request->filled('phone_2')) {
            $company->phone_2 = $request->phone_2;
        }
        if ($request->filled('phone')) {
            $company->phone = $request->phone;
        }

        if ($request->filled('address_line2')) {
            $company->address_line2 = $request->address_line2;
        }

        if ($request->filled('social_facebook')) {
            $company->social_facebook = $request->social_facebook;
        }

        if ($request->filled('social_x')) {
            $company->social_x = $request->social_x;
        }

        if ($request->filled('social_youtube')) {
            $company->social_youtube = $request->social_youtube;
        }
        if ($request->filled('video_url')) {
            $company->video_url = $request->video_url;
        }

        $company->save();
        $companies = Company::orderBy('id', 'desc')->get();
        return CompanyResource::collection($companies);
    }
    public function update(Company $company, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'description' => 'required',
            'phone' => 'required',
            'address_line1' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
        ]);
        // 'required|mimes:doc,docx,odt,pdf|max:2048'

        $company->name = $request->name;
        $company->email = $request->email;
        $company->description = $request->description;
        $company->phone = $request->phone;
        $company->address_line1 = $request->address_line1;
        $company->city = $request->city;
        $company->zip_code = $request->zip_code;
        $company->video_url = $request->video_url;

        if ($request->filled('states')) {
            $company->state_id = $request->state["id"];
        }

        if ($request->filled('services')) {
            foreach ($request->services as $key => $service) {
                $company->services()->syncWithoutDetaching($service["id"]);
            }
        }
        if ($request->filled('categories')) {
            foreach ($request->categories as $key => $category) {
                $company->categories()->syncWithoutDetaching($category["id"]);
            }
        }
        if ($request->filled('phone_2')) {
            $company->phone_2 = $request->phone_2;
        }
        if ($request->filled('phone')) {
            $company->phone = $request->phone;
        }

        if ($request->filled('address_line2')) {
            $company->address_line2 = $request->address_line2;
        }

        if ($request->filled('social_facebook')) {
            $company->social_facebook = $request->social_facebook;
        } else {
            $company->social_facebook = '';
        }

        if ($request->filled('social_x')) {
            $company->social_x = $request->social_x;
        } else {
            $company->social_x = '';
        }

        if ($request->filled('social_youtube')) {
            $company->social_youtube = $request->social_youtube;
        } else {
            $company->social_youtube = '';
        }

        if ($request->filled('video_url')) {
            $company->video_url = $request->video_url;
        } else {
            $company->video_url = '';
        }

        $company->save();
        $companies = Company::orderBy('id', 'desc')->get();
        return CompanyResource::collection($companies);
    }
    public function addService(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'service' => 'required',
        ]);

        $company = Company::find($request->company_id);
        $service = Service::find($request->service);
        $company->services()->syncWithoutDetaching([
            $request->service => [
                'pause' => 0
            ]
        ]);

        return new UserCompanyResource($company);
    }
    public function addState(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'state_id' => 'required',
        ]);

        $company = Company::find($request->company_id);
        $service = Service::find($request->state_id);
        $company->services()->syncWithoutDetaching([
            $request->service => [
                'pause' => 0
            ]
        ]);

        return new UserCompanyResource($company);
    }
    public function destroyService(Request $request)
    {
        $request->validate([
            'company_id' => 'required',
            'service_id' => 'required',
        ]);

        $company = Company::find($request->company_id);
        $user = auth()->user();
        if(!$user->companies->where('id', $company->id)->count()){
            abort(403);
        }

        $company->services()->detach($request->service_id);

        return new UserCompanyResource($company);
    }

    public function destroy(Company $company)
    {
        $company->destroy($company->id);
        return response('Company deleted', 201);
    }

    public function getConfiguration(Company $company)
    {

        return new CompanyConfigurationResource($company);
    }
    public function getService($slug, $company_id)
    {
        $service = Service::where('slug', $slug)->first();
        $states = $service->states()->where('company_id', $company_id)->get();
        $states->map(function ($state) use ($service, $company_id) {

            $state->regions = $state->regions()->map(function ($region) use ($service, $company_id, $state) {

                $serviceZipCodes = CompanyServiceZip::where('service_id', $service->id)
                    ->where('company_id', $company_id)->where('region_text', $region)->where('state_iso', $state->iso_code)
                    ->get();
                $serviceZipCodes = $serviceZipCodes->map(function ($zipcodes) {
                    return $zipcodes->zipcode;
                });


                return ["name" => $region["name"], "zipcodes" => $serviceZipCodes, "zipTotal" => count($serviceZipCodes)];
            });

            $zipcodesCount = 0;
            $zipcodesCount = collect($state->regions)->reduce(function ($sum, $region) {
                return $sum + count($region['zipcodes']);
            }, 0);

            $state->areasTotal = $zipcodesCount;
            // if($state->region->zipcodes){
            //     $state->region_count = 3;
            // }
            return $state;
        });
        $service->states = $states;
        return new CompanyServiceResource($service);
    }
}

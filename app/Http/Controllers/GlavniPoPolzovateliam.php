<?php


namespace App\Http\Controllers;

use App\Http\Requests\ChangeActivationStatusRequest;
use App\Http\Requests\PoiskPolzovateliaZapros;
use App\Http\Requests\ZaprosObnovleniaPasportaPolzovatelia;
use App\Http\Requests\ZaprosObnivleniaPolzovatelia;
use App\Models\User;
use App\Khranilischa\RoliPolzovatelei;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Faili\ServisZagruzkiFailov;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GlavniPoPolzovateliam extends Controller
{
    public function __construct(
        private readonly ServisAutentificatcii $authenticationService,
        private readonly ServisZagruzkiFailov $fileUploadService
    )
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $columns = ['id', 'firstname', 'lastname', 'status', 'avatar', 'role', 'created_at'];
        return User::select($columns)
            ->orderBy('created_at')
            ->orderBy('firstname')
            ->where('role', '!=', RoliPolzovatelei::Ahmad)
            ->paginate();
    }


    public function changeActivationStatus(ChangeActivationStatusRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $user = User::findOrFail(intval($params['user_id']));
            $user->status = $params['status'];

            return response()->json(['message' => $user->update()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ZaprosObnivleniaPolzovatelia $request, User $user)
    {
        $params = $request->validated();
        return ['result' => $user->update($params)];
    }


    public function updatePassport(ZaprosObnovleniaPasportaPolzovatelia $request)
    {
        $passport_image = $request->file('passport_image');
        $auth_user = $this->authenticationService->poluchitPolzovatelia($request->bearerToken());

        $uploaded = $this->fileUploadService->zagruzit($passport_image, $auth_user);
        $user = User::find($auth_user->id);

        $result = $user?->update([
            'passport_image' => $uploaded->generated_name
        ]);

        return ['result' => $result];
    }


    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    public function search(PoiskPolzovateliaZapros $request)
    {
        $searchTerm = $request->validated('username', '');

        return User::where('lastname', 'LIKE', "%$searchTerm%")
            ->orWhere('firstname', 'LIKE', "%$searchTerm%")
            ->paginate();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return ['result' => $user->delete(), 'user' => $user];
    }
}

<?php


namespace App\Http\Controllers;

use App\Http\Requests\SokranitFailZapros;
use App\Models\File;
use App\Servsi\Authentication\ServisAutentificatcii;
use App\Servsi\Faili\ServisZagruzkiFailov;
use Illuminate\Http\Request;


class GlavniPoFailam extends Controller
{
    public function __construct(
        private readonly ServisAutentificatcii $authenticationService,
        private readonly ServisZagruzkiFailov  $servisZagruzkiFailov,
    )
    {

    }


    public function index()
    {
        return File::with('user')->paginate();
    }


    public function store(SokranitFailZapros $request)
    {
        $user = $this->authenticationService->poluchitPolzovatelia($request->bearerToken());
        $destination = $request->validated('dest', 'uploads');
        $file = $request->validated('file');

        return $this->servisZagruzkiFailov->zagruzit($file, $user, $destination)->toArray();
    }

    public function poiskFailov(Request $request)
    {
        $filename = $request->get('filename');

        return File::where('original_name', 'LIKE', "%$filename%")->get();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function udalitFail(File $file): array
    {
        if ($file->object_source == 'account.avatar') {
            $put_k_failu = public_path('/images/avatars/' . $file->generated_name);
        } else {
            $put_k_failu = public_path('/upload/' . $file->generated_name);
        }

        unlink($put_k_failu);

        return ['status' => $file->delete()];
    }
}

<?php
 

namespace App\Http\Controllers\Sotrudniki;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sotrudniki\ObnovitSotrudnikaZapros;
use App\Models\User;
use App\Khranilischa\RoliPolzovatelei;
use App\Khranilischa\UserStatus;

class GlavniPoSotrudnikam extends Controller
{
    public function poluchitVsekh()
    {
        return User::whereNotIn('role', [RoliPolzovatelei::Ahmad, RoliPolzovatelei::Klient])->paginate();
    }

    public function PolushitPoId(int $id)
    {
        return User::with(['city', 'office'])->find($id);
    }


    public function poluchitKurierov()
    {
        return User::where(['role' => RoliPolzovatelei::Voditel, 'status' => UserStatus::Activated])->paginate();
    }

    public function obnovit(ObnovitSotrudnikaZapros $request, int $id)
    {
        $attributes = $request->validated();

        $user = User::find($id);
        $r = $user?->update($attributes);
        return response()->json(['result' => $r]);

    }
}

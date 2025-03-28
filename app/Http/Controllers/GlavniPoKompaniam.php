<?php


namespace App\Http\Controllers;

use App\Http\Requests\ZaprosFirmi;
use App\Models\Company;

class GlavniPoKompaniam extends Controller
{
    public function __construct(
    )
    {
    }

    public function index()
    {
        return Company::paginate();
    }


    public function create(ZaprosFirmi $request)
    {
        $validated = $request->validated();
        return Company::create($validated);
    }

    public function get(Company $model)
    {
        return $model;
    }

    public function update(Company $model, ZaprosFirmi $request)
    {
        $validated = $request->validated();
        return ['result' => $model->update($validated)];
    }
}

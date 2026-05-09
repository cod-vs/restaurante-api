<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function show()
    {
        return response()->json(Restaurante::first());
    }

    public function update(Request $request)
    {
        $restaurante = Restaurante::first();

        $restaurante->update($request->validate([
            'name'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
        ]));

        return response()->json($restaurante);
    }
}

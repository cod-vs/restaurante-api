<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
        return response()->json(Restaurante::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        return response()->json(Restaurante::create($data), 201);
    }

    public function show(Restaurante $restaurante)
    {
        return response()->json($restaurante->load('productos'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $restaurante->update($request->validate([
            'name'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
        ]));

        return response()->json($restaurante);
    }

    public function destroy(Restaurante $restaurante)
    {
        $restaurante->delete();

        return response()->json(['message' => 'Restaurante deleted.']);
    }
}

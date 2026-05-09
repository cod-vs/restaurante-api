<?php

namespace App\Http\Controllers;

use App\Models\Restaurante;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
        return response()->json(auth()->user()->restaurantes);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $restaurante = auth()->user()->restaurantes()->create($data);

        return response()->json($restaurante, 201);
    }

    public function show(Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        return response()->json($restaurante->load('productos'));
    }

    public function update(Request $request, Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        $data = $request->validate([
            'name'    => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
        ]);

        $restaurante->update($data);

        return response()->json($restaurante);
    }

    public function destroy(Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);
        $restaurante->delete();

        return response()->json(['message' => 'Restaurante deleted.']);
    }

    private function authorizeRestaurante(Restaurante $restaurante)
    {
        if ($restaurante->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
}

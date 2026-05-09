<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        return response()->json($restaurante->productos);
    }

    public function store(Request $request, Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $producto = $restaurante->productos()->create($data);

        return response()->json($producto, 201);
    }

    public function show(Restaurante $restaurante, Producto $producto)
    {
        $this->authorizeRestaurante($restaurante);

        return response()->json($producto);
    }

    public function update(Request $request, Restaurante $restaurante, Producto $producto)
    {
        $this->authorizeRestaurante($restaurante);

        $data = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $producto->update($data);

        return response()->json($producto);
    }

    public function destroy(Restaurante $restaurante, Producto $producto)
    {
        $this->authorizeRestaurante($restaurante);
        $producto->delete();

        return response()->json(['message' => 'Producto deleted.']);
    }

    private function authorizeRestaurante(Restaurante $restaurante)
    {
        if ($restaurante->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
}

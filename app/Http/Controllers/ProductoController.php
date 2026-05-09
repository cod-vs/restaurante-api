<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Restaurante;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Restaurante $restaurante)
    {
        return response()->json($restaurante->productos);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurante_id' => 'required|exists:restaurantes,id',
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
        ]);

        return response()->json(Producto::create($data), 201);
    }

    public function update(Request $request, Producto $producto)
    {
        $producto->update($request->validate([
            'name'  => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
        ]));

        return response()->json($producto);
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json(['message' => 'Producto deleted.']);
    }
}

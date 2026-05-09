<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        return response()->json(Producto::all());
    }

    public function store(Request $request)
    {
        return response()->json(Producto::create($request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ])), 201);
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

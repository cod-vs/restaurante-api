<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Restaurante $restaurante)
    {
        return response()->json(
            $restaurante->orders()->with('orderItems.producto')->latest()->get()
        );
    }

    public function today(Restaurante $restaurante)
    {
        return response()->json(
            $restaurante->orders()->with('orderItems.producto')->whereDate('created_at', today())->latest()->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'restaurante_id'      => 'required|exists:restaurantes,id',
            'notes'               => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.quantity'    => 'required|integer|min:1|max:255',
        ]);

        $order = DB::transaction(function () use ($data) {
            $total = 0;
            $itemsToInsert = [];

            foreach ($data['items'] as $item) {
                $producto   = Producto::findOrFail($item['producto_id']);
                $total     += $producto->price * $item['quantity'];

                $itemsToInsert[] = [
                    'producto_id' => $producto->id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $producto->price,
                ];
            }

            $order = Order::create([
                'restaurante_id' => $data['restaurante_id'],
                'status'         => 'pending',
                'total'          => $total,
                'notes'          => $data['notes'] ?? null,
            ]);

            $order->orderItems()->createMany($itemsToInsert);

            return $order->load('orderItems.producto');
        });

        return response()->json($order, 201);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->update($request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]));

        return response()->json($order);
    }
}

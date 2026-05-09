<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        $orders = $restaurante->orders()
            ->with('orderItems.producto')
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function today(Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        $orders = $restaurante->orders()
            ->with('orderItems.producto')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return response()->json($orders);
    }

    public function store(Request $request, Restaurante $restaurante)
    {
        $this->authorizeRestaurante($restaurante);

        $data = $request->validate([
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.quantity'    => 'required|integer|min:1|max:255',
        ]);

        $order = DB::transaction(function () use ($data, $restaurante) {
            $total = 0;
            $itemsToInsert = [];

            foreach ($data['items'] as $item) {
                $producto = $restaurante->productos()->findOrFail($item['producto_id']);
                $unitPrice = $producto->price;
                $total += $unitPrice * $item['quantity'];

                $itemsToInsert[] = [
                    'producto_id' => $producto->id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $unitPrice,
                ];
            }

            $order = $restaurante->orders()->create([
                'user_id' => auth()->id(),
                'status'  => 'pending',
                'total'   => $total,
                'notes'   => $data['notes'] ?? null,
            ]);

            $order->orderItems()->createMany($itemsToInsert);

            return $order->load('orderItems.producto');
        });

        return response()->json($order, 201);
    }

    public function updateStatus(Request $request, Restaurante $restaurante, Order $order)
    {
        $this->authorizeRestaurante($restaurante);

        $data = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order->update($data);

        return response()->json($order);
    }

    private function authorizeRestaurante(Restaurante $restaurante)
    {
        if ($restaurante->user_id !== auth()->id()) {
            abort(403, 'Unauthorized.');
        }
    }
}

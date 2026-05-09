<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function daily()
    {
        return $this->getSummary('day');
    }

    public function weekly()
    {
        return $this->getSummary('week');
    }

    public function monthly()
    {
        return $this->getSummary('month');
    }

    public function yearly()
    {
        return $this->getSummary('year');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'notes'               => 'nullable|string',
            'items'               => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.quantity'    => 'required|integer|min:1|max:255',
        ]);

        $order = DB::transaction(function () use ($data) {
            $total         = 0;
            $itemsToInsert = [];

            foreach ($data['items'] as $item) {
                $producto        = Producto::findOrFail($item['producto_id']);
                $total          += $producto->price * $item['quantity'];
                $itemsToInsert[] = [
                    'producto_id' => $producto->id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $producto->price,
                ];
            }

            $order = Order::create([
                'total' => $total,
                'notes' => $data['notes'] ?? null,
            ]);

            $order->orderItems()->createMany($itemsToInsert);

            return $order->load('orderItems.producto');
        });

        return response()->json($order, 201);
    }

    public function filter(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|date',
            'to'   => 'required|date|after_or_equal:from',
        ]);

        return $this->getSummary('custom', $data['from'], $data['to']);
    }

    private function getSummary(string $period, string $from = null, string $to = null)
    {
        $start = $from ? now()->parse($from)->startOfDay() : now()->startOf($period);
        $end   = $to   ? now()->parse($to)->endOfDay()     : now()->endOf($period);

        $products = DB::table('order_items')
            ->join('productos', 'productos.id', '=', 'order_items.producto_id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereBetween('orders.created_at', [$start, $end])
            ->select(
                'productos.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_revenue')
            )
            ->groupBy('productos.id', 'productos.name')
            ->orderByDesc('total_revenue')
            ->get();

        return response()->json([
            'period'        => $period,
            'total_orders'  => Order::whereBetween('created_at', [$start, $end])->count(),
            'products'      => $products->map(fn($p) => [
                'name'           => $p->name,
                'total_quantity' => (int) $p->total_quantity,
                'total_revenue'  => round((float) $p->total_revenue, 2),
            ]),
            'total_revenue' => round($products->sum('total_revenue'), 2),
        ]);
    }
}

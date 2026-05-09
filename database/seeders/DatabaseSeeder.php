<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Producto;
use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create()->each(function (User $user) {

            Restaurante::factory(2)->create(['user_id' => $user->id])->each(function (Restaurante $restaurante) use ($user) {

                $productos = Producto::factory(5)->create(['restaurante_id' => $restaurante->id]);

                Order::factory(3)->create([
                    'user_id'        => $user->id,
                    'restaurante_id' => $restaurante->id,
                ])->each(function (Order $order) use ($productos) {

                    $items   = $productos->random(2);
                    $total   = 0;

                    foreach ($items as $producto) {
                        $quantity  = rand(1, 5);
                        $unitPrice = $producto->price;
                        $total    += $quantity * $unitPrice;

                        $order->orderItems()->create([
                            'producto_id' => $producto->id,
                            'quantity'    => $quantity,
                            'unit_price'  => $unitPrice,
                        ]);
                    }

                    $order->update(['total' => $total]);
                });
            });
        });
    }
}

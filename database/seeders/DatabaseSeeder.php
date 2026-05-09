<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Producto;
use App\Models\Restaurante;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@restaurante.com',
            'password' => Hash::make('password'),
        ]);

        Restaurante::factory(3)->create()->each(function (Restaurante $restaurante) {

            $productos = Producto::factory(5)->create(['restaurante_id' => $restaurante->id]);

            Order::factory(5)->create(['restaurante_id' => $restaurante->id])->each(function (Order $order) use ($productos) {
                $total = 0;

                foreach ($productos->random(2) as $producto) {
                    $quantity = rand(1, 5);
                    $total   += $producto->price * $quantity;

                    $order->orderItems()->create([
                        'producto_id' => $producto->id,
                        'quantity'    => $quantity,
                        'unit_price'  => $producto->price,
                    ]);
                }

                $order->update(['total' => $total]);
            });
        });
    }
}

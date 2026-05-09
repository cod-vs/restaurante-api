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

        Restaurante::create([
            'name'    => 'Mi Restaurante',
            'address' => '123 Main Street',
        ]);

        $productos = Producto::factory(8)->create();

        $dates = [
            now(),
            now()->subDays(2),
            now()->subWeeks(2),
            now()->subMonths(2),
            now()->subMonths(6),
            now()->subYear(),
        ];

        foreach ($dates as $date) {
            Order::factory(3)->create(['status' => 'completed', 'created_at' => $date])->each(function (Order $order) use ($productos, $date) {
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

                $order->update(['total' => $total, 'updated_at' => $date]);
            });
        }
    }
}

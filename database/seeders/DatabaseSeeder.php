<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Usuario demo ──────────────────────────────────────────
        $user = User::create([
            'name'     => 'Admin Demo',
            'email'    => 'admin@minicrm.test',
            'password' => Hash::make('password'),
        ]);

        // ── 30 clientes (3 páginas a 10/página) con 1-3 contactos cada uno ──
        Client::factory(30)->create(['user_id' => $user->id])->each(function (Client $client) use ($user) {
            $count = fake()->numberBetween(1, 3);

            // Primer contacto siempre es primario
            Contact::factory()->create([
                'client_id'  => $client->id,
                'user_id'    => $user->id,
                'is_primary' => true,
            ]);

            // Contactos adicionales (no primarios)
            if ($count > 1) {
                Contact::factory($count - 1)->create([
                    'client_id'  => $client->id,
                    'user_id'    => $user->id,
                    'is_primary' => false,
                ]);
            }
        });

        $this->command->info("✓ Usuario demo: admin@minicrm.test / password");
        $this->command->info("✓ Clientes: " . Client::count());
        $this->command->info("✓ Contactos: " . Contact::count());
    }
}

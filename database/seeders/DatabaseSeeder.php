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
        $user = User::firstOrCreate(
            ['email' => 'admin@minicrm.test'],
            ['name' => 'Admin Demo', 'password' => Hash::make('password')]
        );

        // Idempotente: no vuelve a sembrar si ya hay clientes
        if (Client::where('user_id', $user->id)->exists()) {
            $this->command->info('Seeder ya ejecutado — omitiendo.');
            return;
        }

        $clients = [
            ['name' => 'TechCorp SA',        'company' => 'TechCorp',         'email' => 'info@techcorp.com',       'phone' => '555-0101', 'status' => 'activo'],
            ['name' => 'Innova Solutions',    'company' => 'Innova Solutions',  'email' => 'hello@innova.io',         'phone' => '555-0102', 'status' => 'activo'],
            ['name' => 'Digital Plus',        'company' => 'Digital Plus',      'email' => 'contact@digitalplus.net', 'phone' => '555-0103', 'status' => 'inactivo'],
            ['name' => 'Grupo Alfa',          'company' => 'Grupo Alfa',        'email' => 'info@grupoalfa.com',      'phone' => '555-0104', 'status' => 'prospecto'],
            ['name' => 'NextGen Labs',        'company' => 'NextGen Labs',      'email' => 'hi@nextgenlabs.io',       'phone' => '555-0105', 'status' => 'prospecto'],
            ['name' => 'Visión Global',       'company' => 'Visión Global',     'email' => 'info@visionglobal.com',   'phone' => '555-0106', 'status' => 'activo'],
            ['name' => 'Alianza Corp',        'company' => 'Alianza Corp',      'email' => 'ops@alianzacorp.com',    'phone' => '555-0107', 'status' => 'prospecto'],
            ['name' => 'Metro Systems',       'company' => 'Metro Systems',     'email' => 'admin@metrosys.net',     'phone' => '555-0108', 'status' => 'activo'],
            ['name' => 'Consult Pro',         'company' => 'Consult Pro',       'email' => 'info@consultpro.com',    'phone' => '555-0109', 'status' => 'inactivo'],
            ['name' => 'DataBridge Inc',      'company' => 'DataBridge',        'email' => 'hello@databridge.io',    'phone' => '555-0110', 'status' => 'prospecto'],
        ];

        $contacts = [
            [['name' => 'Carlos García',   'email' => 'cgarcia@techcorp.com',       'phone' => '555-1001', 'position' => 'Director General'],
             ['name' => 'María López',      'email' => 'mlopez@techcorp.com',        'phone' => '555-1002', 'position' => 'Gerente de Ventas']],

            [['name' => 'Luis Martínez',   'email' => 'lmartinez@innova.io',        'phone' => '555-1003', 'position' => 'CEO'],
             ['name' => 'Ana Rodríguez',   'email' => 'arodriguez@innova.io',       'phone' => '555-1004', 'position' => 'CTO']],

            [['name' => 'Jorge González',  'email' => 'jgonzalez@digitalplus.net',  'phone' => '555-1005', 'position' => 'Fundador'],
             ['name' => 'Patricia Pérez',  'email' => 'pperez@digitalplus.net',     'phone' => '555-1006', 'position' => 'Directora Financiera']],

            [['name' => 'Miguel Torres',   'email' => 'mtorres@grupoalfa.com',      'phone' => '555-1007', 'position' => 'Presidente'],
             ['name' => 'Laura Ramírez',   'email' => 'lramirez@grupoalfa.com',     'phone' => '555-1008', 'position' => 'VP Operaciones']],

            [['name' => 'Diego Flores',    'email' => 'dflores@nextgenlabs.io',     'phone' => '555-1009', 'position' => 'CEO'],
             ['name' => 'Sandra Morales',  'email' => 'smorales@nextgenlabs.io',    'phone' => '555-1010', 'position' => 'Product Manager']],

            [['name' => 'Roberto Vargas',  'email' => 'rvargas@visionglobal.com',   'phone' => '555-1011', 'position' => 'Director Comercial'],
             ['name' => 'Elena Castillo',  'email' => 'ecastillo@visionglobal.com', 'phone' => '555-1012', 'position' => 'Ejecutiva de Cuentas']],

            [['name' => 'Andrés Jiménez', 'email' => 'ajimenez@alianzacorp.com',   'phone' => '555-1013', 'position' => 'Socio Director'],
             ['name' => 'Carmen Ruiz',     'email' => 'cruiz@alianzacorp.com',      'phone' => '555-1014', 'position' => 'Coordinadora']],

            [['name' => 'Felipe Mendoza',  'email' => 'fmendoza@metrosys.net',      'phone' => '555-1015', 'position' => 'CTO'],
             ['name' => 'Claudia Herrera', 'email' => 'cherrera@metrosys.net',      'phone' => '555-1016', 'position' => 'Project Manager']],

            [['name' => 'Javier Cruz',     'email' => 'jcruz@consultpro.com',       'phone' => '555-1017', 'position' => 'Senior Consultant'],
             ['name' => 'Isabel Reyes',    'email' => 'ireyes@consultpro.com',      'phone' => '555-1018', 'position' => 'Analista']],

            [['name' => 'Pablo Sánchez',   'email' => 'psanchez@databridge.io',     'phone' => '555-1019', 'position' => 'Head of Engineering'],
             ['name' => 'Valentina Díaz',  'email' => 'vdiaz@databridge.io',        'phone' => '555-1020', 'position' => 'Data Scientist']],
        ];

        foreach ($clients as $i => $clientData) {
            $client = Client::create(array_merge($clientData, ['user_id' => $user->id]));

            foreach ($contacts[$i] as $j => $contactData) {
                Contact::create(array_merge($contactData, [
                    'client_id'  => $client->id,
                    'user_id'    => $user->id,
                    'is_primary' => $j === 0,
                ]));
            }
        }

        $this->command->info("✓ Usuario demo: admin@minicrm.test / password");
        $this->command->info("✓ Clientes: " . Client::count());
        $this->command->info("✓ Contactos: " . Contact::count());
    }
}

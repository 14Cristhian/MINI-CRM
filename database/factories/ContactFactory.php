<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    private static array $firstNames = [
        'Carlos', 'María', 'Juan', 'Ana', 'Luis', 'Laura', 'Jorge', 'Paula',
        'Andrés', 'Camila', 'Felipe', 'Valentina', 'Diego', 'Isabella', 'Sergio',
        'Natalia', 'Ricardo', 'Daniela', 'Alejandro', 'Juliana', 'Mónica', 'Tomás',
        'Paola', 'Miguel', 'Claudia', 'Roberto', 'Sandra', 'Héctor', 'Patricia', 'Iván',
    ];

    private static array $lastNames = [
        'García', 'Martínez', 'López', 'Rodríguez', 'González', 'Pérez',
        'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez',
        'Díaz', 'Reyes', 'Morales', 'Jiménez', 'Herrera', 'Medina',
        'Castro', 'Vargas', 'Ramos', 'Ortiz', 'Rubio', 'Fernández',
    ];

    private static array $positions = [
        'Director General', 'Gerente Comercial', 'Gerente de Ventas',
        'Jefe de Compras', 'Director de Marketing', 'Coordinador de Proyectos',
        'Analista de Negocios', 'Ejecutivo de Cuenta', 'Asesor Comercial',
        'Director Financiero', 'Gerente de Operaciones', 'VP de Tecnología',
        'Chief Executive Officer', 'Chief Financial Officer', 'Gerente Regional',
    ];

    public function definition(): array
    {
        $firstName = self::$firstNames[array_rand(self::$firstNames)];
        $lastName  = self::$lastNames[array_rand(self::$lastNames)];

        return [
            'name'       => "{$firstName} {$lastName}",
            'email'      => $this->faker->unique()->safeEmail(),
            'phone'      => '+57 3' . $this->faker->numerify('## ### ####'),
            'position'   => self::$positions[array_rand(self::$positions)],
            'is_primary' => false,
            'client_id'  => Client::factory(),
            'user_id'    => 1,
        ];
    }
}

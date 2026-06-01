<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    private static array $companies = [
        'Tecnología Avanzada', 'Soluciones Digitales', 'Grupo Empresarial',
        'Innovaciones Tech', 'Consulting Group', 'Digital Solutions',
        'Negocios Globales', 'Servicios Integrados', 'Distribuidora Nacional',
        'Constructora Moderna', 'Inversiones Estratégicas', 'Logística Express',
        'Comercializadora Plus', 'Manufactura Industrial', 'Retail Ventures',
        'Finanzas Corporativas', 'Marketing Digital', 'Salud y Bienestar',
        'Educación Continua', 'Transporte y Carga', 'Agropecuaria del Sur',
        'Importaciones Premium', 'Exportaciones Globales', 'Telecomunicaciones Pro',
        'Energía Renovable', 'Alimentos Naturales', 'Moda y Estilo',
        'Turismo Experiencial', 'Bienes Raíces Capital', 'Seguros y Finanzas',
    ];

    private static array $suffixes = [
        'S.A.S', 'Ltda', 'S.A', 'Corp', 'Group', 'SRL', 'Inc', 'SpA', 'CIA',
    ];

    private static array $firstNames = [
        'Carlos', 'María', 'Juan', 'Ana', 'Luis', 'Laura', 'Jorge', 'Paula',
        'Andrés', 'Camila', 'Felipe', 'Valentina', 'Diego', 'Isabella', 'Sergio',
        'Natalia', 'Ricardo', 'Daniela', 'Alejandro', 'Juliana',
    ];

    private static array $lastNames = [
        'García', 'Martínez', 'López', 'Rodríguez', 'González', 'Pérez',
        'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez',
        'Díaz', 'Reyes', 'Morales', 'Jiménez', 'Herrera', 'Medina',
        'Castro', 'Vargas',
    ];

    public function definition(): array
    {
        $firstName = self::$firstNames[array_rand(self::$firstNames)];
        $lastName  = self::$lastNames[array_rand(self::$lastNames)];
        $company   = self::$companies[array_rand(self::$companies)];
        $suffix    = self::$suffixes[array_rand(self::$suffixes)];
        $slug      = strtolower(preg_replace('/\s+/', '', $company));
        $domain    = $slug . '.co';
        $emailName = strtolower($firstName) . '.' . strtolower($lastName);

        return [
            'name'    => "{$firstName} {$lastName}",
            'email'   => $this->faker->unique()->safeEmail(),
            'phone'   => '+57 3' . $this->faker->numerify('## ### ####'),
            'company' => "{$company} {$suffix}",
            'status'  => $this->faker->randomElement(['activo', 'activo', 'activo', 'inactivo', 'prospecto', 'prospecto']),
            'notes'   => $this->faker->optional(0.6)->sentence(10),
            'user_id' => 1,
        ];
    }

    public function activo(): static
    {
        return $this->state(['status' => 'activo']);
    }

    public function inactivo(): static
    {
        return $this->state(['status' => 'inactivo']);
    }

    public function prospecto(): static
    {
        return $this->state(['status' => 'prospecto']);
    }
}

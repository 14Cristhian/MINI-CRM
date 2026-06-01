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

        if (Client::where('user_id', $user->id)->exists()) {
            $this->command->info('Seeder ya ejecutado — omitiendo.');
            return;
        }

        $data = [
            // activos
            ['client' => ['name'=>'TechCorp SA',        'company'=>'TechCorp',          'email'=>'info@techcorp.com',        'phone'=>'555-0101','status'=>'activo'],
             'contacts' => [
                ['name'=>'Carlos García',   'email'=>'cgarcia@techcorp.com',   'phone'=>'555-1001','position'=>'Director General',    'primary'=>true],
                ['name'=>'María López',     'email'=>'mlopez@techcorp.com',    'phone'=>'555-1002','position'=>'Gerente de Ventas',   'primary'=>false],
             ]],
            ['client' => ['name'=>'Innova Solutions',   'company'=>'Innova',             'email'=>'hello@innova.io',          'phone'=>'555-0102','status'=>'activo'],
             'contacts' => [
                ['name'=>'Luis Martínez',   'email'=>'lmartinez@innova.io',    'phone'=>'555-1003','position'=>'CEO',                 'primary'=>true],
                ['name'=>'Ana Rodríguez',   'email'=>'arodriguez@innova.io',   'phone'=>'555-1004','position'=>'CTO',                 'primary'=>false],
             ]],
            ['client' => ['name'=>'Visión Global',      'company'=>'Visión Global',      'email'=>'ops@visionglobal.com',     'phone'=>'555-0103','status'=>'activo'],
             'contacts' => [
                ['name'=>'Roberto Vargas',  'email'=>'rvargas@visionglobal.com','phone'=>'555-1005','position'=>'Director Comercial', 'primary'=>true],
                ['name'=>'Elena Castillo',  'email'=>'ecastillo@visionglobal.com','phone'=>'555-1006','position'=>'Ejecutiva de Cuentas','primary'=>false],
             ]],
            ['client' => ['name'=>'Metro Systems',      'company'=>'Metro Systems',      'email'=>'admin@metrosys.net',       'phone'=>'555-0104','status'=>'activo'],
             'contacts' => [
                ['name'=>'Felipe Mendoza',  'email'=>'fmendoza@metrosys.net',  'phone'=>'555-1007','position'=>'CTO',                 'primary'=>true],
                ['name'=>'Claudia Herrera', 'email'=>'cherrera@metrosys.net',  'phone'=>'555-1008','position'=>'Project Manager',     'primary'=>false],
             ]],
            ['client' => ['name'=>'Soluciones Ágil',   'company'=>'Ágil',               'email'=>'info@agil.com.co',         'phone'=>'555-0105','status'=>'activo'],
             'contacts' => [
                ['name'=>'Tomás Fuentes',   'email'=>'tfuentes@agil.com.co',   'phone'=>'555-1009','position'=>'Gerente General',     'primary'=>true],
                ['name'=>'Pilar Acosta',    'email'=>'pacosta@agil.com.co',    'phone'=>'555-1010','position'=>'Analista Senior',     'primary'=>false],
             ]],
            ['client' => ['name'=>'CloudBase Inc',      'company'=>'CloudBase',          'email'=>'hello@cloudbase.io',       'phone'=>'555-0106','status'=>'activo'],
             'contacts' => [
                ['name'=>'Sebastián Mora',  'email'=>'smora@cloudbase.io',     'phone'=>'555-1011','position'=>'Head of Engineering','primary'=>true],
                ['name'=>'Natalia Ríos',    'email'=>'nrios@cloudbase.io',     'phone'=>'555-1012','position'=>'DevOps Lead',         'primary'=>false],
             ]],
            ['client' => ['name'=>'Apex Consulting',    'company'=>'Apex',               'email'=>'contact@apexcorp.com',     'phone'=>'555-0107','status'=>'activo'],
             'contacts' => [
                ['name'=>'Ricardo Blanco',  'email'=>'rblanco@apexcorp.com',   'phone'=>'555-1013','position'=>'Senior Consultant',   'primary'=>true],
                ['name'=>'Gabriela Soto',   'email'=>'gsoto@apexcorp.com',     'phone'=>'555-1014','position'=>'Junior Consultant',   'primary'=>false],
             ]],
            // prospectos
            ['client' => ['name'=>'Grupo Alfa',         'company'=>'Grupo Alfa',         'email'=>'info@grupoalfa.com',       'phone'=>'555-0108','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Miguel Torres',   'email'=>'mtorres@grupoalfa.com',  'phone'=>'555-1015','position'=>'Presidente',          'primary'=>true],
                ['name'=>'Laura Ramírez',   'email'=>'lramirez@grupoalfa.com', 'phone'=>'555-1016','position'=>'VP Operaciones',      'primary'=>false],
             ]],
            ['client' => ['name'=>'NextGen Labs',       'company'=>'NextGen Labs',       'email'=>'hi@nextgenlabs.io',        'phone'=>'555-0109','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Diego Flores',    'email'=>'dflores@nextgenlabs.io', 'phone'=>'555-1017','position'=>'CEO',                 'primary'=>true],
                ['name'=>'Sandra Morales',  'email'=>'smorales@nextgenlabs.io','phone'=>'555-1018','position'=>'Product Manager',     'primary'=>false],
             ]],
            ['client' => ['name'=>'Alianza Corp',       'company'=>'Alianza',            'email'=>'ops@alianzacorp.com',      'phone'=>'555-0110','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Andrés Jiménez', 'email'=>'ajimenez@alianzacorp.com','phone'=>'555-1019','position'=>'Socio Director',     'primary'=>true],
                ['name'=>'Carmen Ruiz',     'email'=>'cruiz@alianzacorp.com',  'phone'=>'555-1020','position'=>'Coordinadora',        'primary'=>false],
             ]],
            ['client' => ['name'=>'DataBridge Inc',     'company'=>'DataBridge',         'email'=>'hello@databridge.io',      'phone'=>'555-0111','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Pablo Sánchez',   'email'=>'psanchez@databridge.io', 'phone'=>'555-1021','position'=>'Head of Engineering','primary'=>true],
                ['name'=>'Valentina Díaz',  'email'=>'vdiaz@databridge.io',    'phone'=>'555-1022','position'=>'Data Scientist',      'primary'=>false],
             ]],
            ['client' => ['name'=>'FinStream SA',       'company'=>'FinStream',          'email'=>'info@finstream.net',       'phone'=>'555-0112','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Alejandro Vega',  'email'=>'avega@finstream.net',    'phone'=>'555-1023','position'=>'CFO',                 'primary'=>true],
                ['name'=>'Mónica Paredes',  'email'=>'mparedes@finstream.net', 'phone'=>'555-1024','position'=>'Analista Financiero', 'primary'=>false],
             ]],
            ['client' => ['name'=>'Quantum Digital',    'company'=>'Quantum',            'email'=>'team@quantumdigital.io',   'phone'=>'555-0113','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Camila Ortega',   'email'=>'cortega@quantumdigital.io','phone'=>'555-1025','position'=>'CMO',              'primary'=>true],
                ['name'=>'Esteban Cruz',    'email'=>'ecruz@quantumdigital.io','phone'=>'555-1026','position'=>'Growth Manager',      'primary'=>false],
             ]],
            // inactivos
            ['client' => ['name'=>'Digital Plus',       'company'=>'Digital Plus',       'email'=>'contact@digitalplus.net',  'phone'=>'555-0114','status'=>'inactivo'],
             'contacts' => [
                ['name'=>'Jorge González',  'email'=>'jgonzalez@digitalplus.net','phone'=>'555-1027','position'=>'Fundador',         'primary'=>true],
                ['name'=>'Patricia Pérez',  'email'=>'pperez@digitalplus.net', 'phone'=>'555-1028','position'=>'Dir. Financiera',    'primary'=>false],
             ]],
            ['client' => ['name'=>'Consult Pro',        'company'=>'Consult Pro',        'email'=>'info@consultpro.com',      'phone'=>'555-0115','status'=>'inactivo'],
             'contacts' => [
                ['name'=>'Javier Cruz',     'email'=>'jcruz@consultpro.com',   'phone'=>'555-1029','position'=>'Senior Consultant',  'primary'=>true],
                ['name'=>'Isabel Reyes',    'email'=>'ireyes@consultpro.com',  'phone'=>'555-1030','position'=>'Analista',            'primary'=>false],
             ]],
            ['client' => ['name'=>'OldMedia Group',     'company'=>'OldMedia',           'email'=>'info@oldmedia.com',        'phone'=>'555-0116','status'=>'inactivo'],
             'contacts' => [
                ['name'=>'Beatriz Lara',    'email'=>'blara@oldmedia.com',     'phone'=>'555-1031','position'=>'Directora',          'primary'=>true],
             ]],
            ['client' => ['name'=>'RetailMax',          'company'=>'RetailMax',          'email'=>'ops@retailmax.co',         'phone'=>'555-0117','status'=>'inactivo'],
             'contacts' => [
                ['name'=>'Martín Salazar',  'email'=>'msalazar@retailmax.co',  'phone'=>'555-1032','position'=>'Gerente Operaciones','primary'=>true],
                ['name'=>'Diana Herrera',   'email'=>'dherrera@retailmax.co',  'phone'=>'555-1033','position'=>'Supervisora',         'primary'=>false],
             ]],
            // activos extra
            ['client' => ['name'=>'HealthTech Latam',   'company'=>'HealthTech',         'email'=>'info@healthtech.lat',      'phone'=>'555-0118','status'=>'activo'],
             'contacts' => [
                ['name'=>'Dr. Hugo Romero', 'email'=>'hromero@healthtech.lat', 'phone'=>'555-1034','position'=>'Medical Director',   'primary'=>true],
                ['name'=>'Lucía Campos',    'email'=>'lcampos@healthtech.lat', 'phone'=>'555-1035','position'=>'Product Owner',       'primary'=>false],
             ]],
            ['client' => ['name'=>'EduSmart',           'company'=>'EduSmart',           'email'=>'contact@edusmart.edu',     'phone'=>'555-0119','status'=>'activo'],
             'contacts' => [
                ['name'=>'Prof. Sara Ibáñez','email'=>'sibanez@edusmart.edu',  'phone'=>'555-1036','position'=>'Rectora',             'primary'=>true],
                ['name'=>'Julián Ramos',    'email'=>'jramos@edusmart.edu',    'phone'=>'555-1037','position'=>'CTO',                 'primary'=>false],
             ]],
            ['client' => ['name'=>'GreenLogic',         'company'=>'GreenLogic',         'email'=>'hello@greenlogic.ec',      'phone'=>'555-0120','status'=>'activo'],
             'contacts' => [
                ['name'=>'Ximena Palacios', 'email'=>'xpalacios@greenlogic.ec','phone'=>'555-1038','position'=>'Sustainability Lead','primary'=>true],
                ['name'=>'Omar Villalba',   'email'=>'ovillalba@greenlogic.ec','phone'=>'555-1039','position'=>'Ing. de Proyectos',  'primary'=>false],
             ]],
            // prospectos extra
            ['client' => ['name'=>'SpaceWork AR',       'company'=>'SpaceWork',          'email'=>'info@spacework.ar',        'phone'=>'555-0121','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Ignacio Ferraro', 'email'=>'iferraro@spacework.ar',  'phone'=>'555-1040','position'=>'Co-fundador',         'primary'=>true],
                ['name'=>'Florencia Ávila','email'=>'favila@spacework.ar',    'phone'=>'555-1041','position'=>'Diseñadora UX',       'primary'=>false],
             ]],
            ['client' => ['name'=>'PayFlex',            'company'=>'PayFlex',            'email'=>'team@payflex.io',          'phone'=>'555-0122','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Rodrigo Peña',    'email'=>'rpena@payflex.io',       'phone'=>'555-1042','position'=>'CEO',                 'primary'=>true],
             ]],
            ['client' => ['name'=>'AutoBot Tech',       'company'=>'AutoBot',            'email'=>'hello@autobot.tech',       'phone'=>'555-0123','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Gonzalo Quiroz',  'email'=>'gquiroz@autobot.tech',   'phone'=>'555-1043','position'=>'CTO',                 'primary'=>true],
                ['name'=>'Renata Molina',   'email'=>'rmolina@autobot.tech',   'phone'=>'555-1044','position'=>'ML Engineer',         'primary'=>false],
             ]],
            ['client' => ['name'=>'Meraki Studio',      'company'=>'Meraki',             'email'=>'hola@meraki.studio',       'phone'=>'555-0124','status'=>'prospecto'],
             'contacts' => [
                ['name'=>'Verónica Tapia',  'email'=>'vtapia@meraki.studio',   'phone'=>'555-1045','position'=>'Directora Creativa', 'primary'=>true],
                ['name'=>'Óscar Beltrán',   'email'=>'obeltran@meraki.studio', 'phone'=>'555-1046','position'=>'Motion Designer',    'primary'=>false],
             ]],
            ['client' => ['name'=>'Segura Labs',        'company'=>'Segura Labs',        'email'=>'contact@seguralabs.pe',    'phone'=>'555-0125','status'=>'activo'],
             'contacts' => [
                ['name'=>'Adriana Chávez',  'email'=>'achavez@seguralabs.pe',  'phone'=>'555-1047','position'=>'CEO',                 'primary'=>true],
                ['name'=>'Fernando Núñez',  'email'=>'fnunez@seguralabs.pe',   'phone'=>'555-1048','position'=>'Security Analyst',   'primary'=>false],
             ]],
        ];

        foreach ($data as $row) {
            $client = Client::create(array_merge($row['client'], ['user_id' => $user->id]));

            foreach ($row['contacts'] as $c) {
                Contact::create([
                    'name'       => $c['name'],
                    'email'      => $c['email'],
                    'phone'      => $c['phone'],
                    'position'   => $c['position'],
                    'is_primary' => $c['primary'],
                    'client_id'  => $client->id,
                    'user_id'    => $user->id,
                ]);
            }
        }

        $this->command->info("✓ Usuario demo: admin@minicrm.test / password");
        $this->command->info("✓ Clientes: " . Client::count());
        $this->command->info("✓ Contactos: " . Contact::count());
    }
}

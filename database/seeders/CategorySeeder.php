<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Servicios', 'description' => 'Página dedicada a ofrecer suscripciones a servicios específicos.'],
            ['name' => 'Contenido Premium', 'description' => 'Sitio enfocado en ofrecer acceso a contenido exclusivo o premium.'],
            ['name' => 'Causas Sociales', 'description' => 'Página destinada a recaudar fondos para organizaciones sin fines de lucro.'],
            ['name' => 'Proyectos Creativos', 'description' => 'Sitio web que facilita la financiación colectiva para proyectos creativos.'],
            ['name' => 'Cursos Online', 'description' => 'Página que permite suscribirse a cursos educativos en línea.'],
            ['name' => 'Membresías para Comunidades', 'description' => 'Sitio enfocado en ofrecer membresías para acceso a comunidades exclusivas.'],
            ['name' => 'Servicios de Entretenimiento', 'description' => 'Página dedicada a suscripciones para servicios de streaming.'],
            ['name' => 'Investigación Científica', 'description' => 'Sitio web que facilita donaciones para apoyar investigaciones científicas.'],
            ['name' => 'Aplicaciones o Software', 'description' => 'Página que ofrece suscripciones para acceso premium a aplicaciones o software.'],
            ['name' => 'Emergencias o Desastres', 'description' => 'Sitio web dedicado a recaudar fondos para situaciones de emergencia.'],
        ];

        Category::insert($categories);
    }
}

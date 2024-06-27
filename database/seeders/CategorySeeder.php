<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {

        $categories = [
            [
                'name' => 'Servicios',
                'description' => 'Página dedicada a ofrecer suscripciones a servicios específicos, con detalles sobre planes disponibles, beneficios incluidos y opciones de pago.',
            ],
            [
                'name' => 'Contenido Premium',
                'description' => 'Sitio enfocado en ofrecer acceso a contenido exclusivo o premium a través de suscripciones, con muestras del contenido disponible y beneficios de membresía.',
            ],
            [
                'name' => 'Causas Sociales',
                'description' => 'Página destinada a recaudar fondos para organizaciones sin fines de lucro o campañas de caridad, con información sobre la causa, métodos de donación y resultados alcanzados.',
            ],
            [
                'name' => 'Proyectos Creativos',
                'description' => 'Sitio web que facilita la financiación colectiva para proyectos creativos como películas, libros o arte, ofreciendo detalles del proyecto y recompensas para donantes.',
            ],
            [
                'name' => 'Cursos Online',
                'description' => 'Página que permite suscribirse a cursos educativos en línea, con descripciones de cursos, currículos, testimonios y opciones de inscripción.',
            ],
            [
                'name' => 'Membresías para Comunidades',
                'description' => 'Sitio enfocado en ofrecer membresías para acceso a comunidades exclusivas, con beneficios como eventos virtuales, recursos compartidos y networking.',
            ],
            [
                'name' => 'Servicios de Entretenimiento',
                'description' => 'Página dedicada a suscripciones para servicios de streaming de música, películas o videojuegos, con detalles sobre contenido disponible y opciones de suscripción.',
            ],
            [
                'name' => 'Investigación Científica',
                'description' => 'Sitio web que facilita donaciones para apoyar investigaciones científicas, con detalles sobre proyectos, logros pasados y testimonios de científicos.',
            ],
            [
                'name' => 'Aplicaciones o Software',
                'description' => 'Página que ofrece suscripciones para acceso premium a aplicaciones o software, con características avanzadas, soporte prioritario y actualizaciones exclusivas.',
            ],
            [
                'name' => 'Emergencias o Desastres',
                'description' => 'Sitio web dedicado a recaudar fondos para situaciones de emergencia o desastres naturales, proporcionando información actualizada, testimonios de afectados y formas de ayuda.',
            ],
        ];

        Category::query()->insert($categories);
    }
}

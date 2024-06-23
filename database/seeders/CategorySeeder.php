<?php

namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{

    public function run()
    {

        $categories = [
            [
                'name' => 'Donaciones',
                'description' => 'Incluir micrositios destinados a la recolección de donaciones para causas específicas o sin fines de lucro.',
            ],
            [
                'name' => 'Facturación',
                'description' => 'Micrositios utilizados para el pago de facturas emitidas por servicios o productos.
',
            ],
            [
                'name' => 'Suscripciones',
                'description' => 'Micrositios que ofrecen planes de suscripción a servicios o contenidos recurrentes.',
            ],
            [
                'name' => ' Pago Personalizado',
                'description' => 'Micrositios que permiten a los usuarios establecer un monto personalizado para pagos únicos.',
            ],
        ];

        Category::query()->insert($categories);
    }
}

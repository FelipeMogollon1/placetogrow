<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Form;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormFactory extends Factory
{
    protected $model = Form::class;

    public function definition(): array
    {
        return [
            'configuration' => $this->faker->text(),
           ];
    }
}

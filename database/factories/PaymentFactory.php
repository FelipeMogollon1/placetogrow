<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Models\Microsite;
use App\Infrastructure\Persistence\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'reference' => Str::random(40),
            'currency' => 'COP',
            'amount' => $this->faker->numberBetween(1000, 100000),
            'description' => $this->faker->sentence,
            'status' => 'PENDING',
            'microsite_id' => Microsite::factory(),
        ];
    }
}

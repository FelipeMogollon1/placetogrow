<?php

namespace App\Http\Requests\SubscriptionPlan;

use App\Constants\CurrencyTypes;
use App\Constants\SubscriptionPeriods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubscriptionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'string',
                'max:255',
            ],
            'description' => [
                'sometimes',
                'array',
            ],
            'description.*' => [
                'string',
                'max:255',
            ],
            'amount' => [
                'sometimes',
                'numeric',
                'min:1',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'currency' => [
                'required',
                Rule::in(CurrencyTypes::getSelectableCurrencies()),
            ],
            'subscription_period' => [
                'sometimes',
                'string',
                Rule::in(SubscriptionPeriods::getAllSubscriptionPeriods()),
            ],
            'expiration_time' => [
                'sometimes',
                'integer',
                'min:1',
            ],
            'additional_info' => [
                'nullable',
                'string',
                'max:255',
            ],
            'expiration_additional_info' => [
                'nullable',
                'string',
                'max:255',
            ],
            'microsite_id' => [
                'nullable',
                'exists:microsites,id',
            ],
        ];
    }


    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'amount' => 'precio',
            'subscription_period' => 'período de suscripción',
            'expiration_time' => 'expiración',
            'microsite_id' => 'micrositio',
        ];
    }
}

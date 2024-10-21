<?php

namespace App\Http\Requests\Subscription;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
            'reference' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'document_type' => 'nullable|string|max:255',
            'document' => 'nullable|string|max:255',
            'process_url' => 'nullable|url',
            'request_id' => 'nullable|string|max:255',
            'mobile' => 'nullable|numeric|digits_between:7,10',
            'company' => 'nullable|string|max:255',
            'paid_at' => 'nullable|date',
            'token' => 'nullable|string|max:255',
            'sub_token' => 'nullable|string|max:255',
            'franchiseName' => 'nullable|string|max:255',
            'lastDigits' => 'nullable|string|max:255',
            'validUntil' => 'nullable|date',
            'next_billing_date' => 'nullable|date',
            'total_charges' => 'nullable|integer',
            'microsite_id' => 'nullable|exists:microsites,id',
        ];
    }
}

<?php
namespace Database\Seeders;

use App\Infrastructure\Persistence\Models\Form;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        Form::query()->insert([
            [
                'configuration' => json_encode([
                    'type' => 'invoice',
                    'head' => 'url/head',
                    'footer' => 'url/footer',
                    'fields' => [
                        [
                            'type' => 'text',
                            'name' => 'name',
                            'error_message_key' => 'errors.name',
                            'active' => 'true'

                        ],
                        [
                            'type' => 'text',
                            'name' => 'surname',
                            'error_message_key' => 'errors.surname',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'email',
                            'name' => 'email',
                            'error_message_key' => 'errors.email',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'amount',
                            'error_message_key' => 'errors.amount',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'select',
                            'name' => 'document_type',
                            'error_message_key' => 'errors.document_type',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'test',
                            'error_message_key' => 'form.errors.test',
                            'active' => 'false'
                        ],
                        [
                            'type' => 'number',
                            'name' => 'testNumber',
                            'error_message_key' => 'form.errors.number',
                            'active' => 'false'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'document',
                            'error_message_key' => 'errors.document',
                            'active' => 'true'
                        ]
                    ]
                ]),
            ],
            [
                'configuration' => json_encode([
                    'type' => 'donation',
                    'head' => 'url/head',
                    'footer' => 'url/footer',
                    'fields' => [
                        [
                            'type' => 'text',
                            'name' => 'name',
                            'error_message_key' => 'form.errors.name',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'surname',
                            'error_message_key' => 'form.errors.surname',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'email',
                            'name' => 'email',
                            'error_message_key' => 'errors.email',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'amount',
                            'error_message_key' => 'errors.amount',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'select',
                            'name' => 'document_type',
                            'error_message_key' => 'errors.document_type',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'document',
                            'error_message_key' => 'errors.document',
                            'active' => 'true'
                        ]
                    ]
                ]),
            ],
            [
                'configuration' => json_encode([
                    'type' => 'subscription',
                    'head' => 'url/head',
                    'footer' => 'url/footer',
                    'fields' => [
                        [
                            'type' => 'text',
                            'name' => 'name',
                            'error_message_key' => 'errors.name',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'surname',
                            'error_message_key' => 'errors.surname',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'email',
                            'name' => 'email',
                            'error_message_key' => 'errors.email',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'amount',
                            'error_message_key' => 'errors.amount',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'select',
                            'name' => 'document_type',
                            'error_message_key' => 'errors.document_type',
                            'active' => 'true'
                        ],
                        [
                            'type' => 'text',
                            'name' => 'document',
                            'error_message_key' => 'errors.document',
                            'active' => 'true'
                        ]
                    ]
                ]),
            ],
        ]);
    }
}

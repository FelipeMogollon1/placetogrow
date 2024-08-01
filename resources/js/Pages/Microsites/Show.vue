<script setup>
import { ref, reactive, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import { route } from 'ziggy-js';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputComponent from '@/Layouts/Organisms/InputComponent.vue';

const page = usePage();
const microsite = ref(page.props.microsite || {});
const formConfig = reactive(page.props.microsite.form || {});

const constants = {
    documentTypes: ref(page.props.arrayConstants.documentTypes || {}),
    currencyTypes: ref(page.props.arrayConstants.currencyTypes || {}),
};

const getConstants = (field) => {
    switch (field) {
        case 'document_type':
            return constants.documentTypes.value;
        case 'currency_type':
            return constants.currencyTypes.value;
        default:
            return [];
    }
};

const form = useForm({
    id: formConfig.id,
    configuration: formConfig.configuration
});

const formErrors = ref(form.errors);
const updateFieldValue = (name, value) => {
    const field = form.configuration.fields.find(field => field.name === name);
    if (field) {
        field.value = value;
    }
};

const toggleFieldActive = (field) => {
    field.active = field.active === 'true' ? 'false' : 'true';
};

const submit = () => {
    form.put(route('forms.update', { id: form.id }), {
        onError: (errors) => {
            console.log(errors);
        },
    });
};

watch(formConfig, (newConfig) => {
    form.configuration = newConfig.configuration;
}, { deep: true });

</script>

<template>
    <Head :title="$t('detail_microsite')"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('detail_microsite') }}</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('list_microsites') }}
                </Link>
            </div>

            <div class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-lg">
                <div class="mt-6 border-t border-gray-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('microsites_table.slug') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.slug }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('microsites_table.name') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.name }}</dd>
                        </div>
                        <div v-if="microsite.user_id !== null" class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('roles_table.admin') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.user.name }}</dd>
                        </div>

                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.type') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`micrositeTypes.${microsite.microsite_type}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.document_type') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`documentType.${microsite.document_type}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.document') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.document }}</dd>
                        </div>

                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('currency') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`currencies.${microsite.currency}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('payment_expiration_time') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.payment_expiration_time }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.category') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.category.name }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.category_description') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.category.description }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.date') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.enabled_at }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.logo') }} :</dt>
                            <dd v-if="microsite.logo" class="text-md leading-6 text-gray-700 font-semibold">
                                <img class="w-20 h-20 rounded-full shadow" :src="`/storage/${microsite.logo}`" alt="logo">
                            </dd>
                            <PhotoIcon v-else class="w-16 h-16 text-gray-400 object-contain hover:text-gray-600"/>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('form.label') }}</h2>
            </div>

            <main>
                <div class="flex justify-center items-center">
                    <div id="form" class="container bg-white grid grid-cols-2 md:grid-cols-2 gap-4 sm:grid-cols-1 p-4 m-4 rounded-2xl shadow-lg">

                        <div class="col-span-2 flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_header') }}</span></p>
                                    <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" />
                            </label>
                        </div>

                        <div class="col-span-2 flex">
                            <div class="text-lg font-semibold">
                                {{ $t('form.electronic_payments') }}
                            </div>
                        </div>
                        <div class="col-span-2 flex">
                            <div class="text-md pb-2">
                                {{ $t('form.additional_information') }}
                            </div>
                        </div>

                        <template v-for="field in form.configuration.fields" :key="field.name">
                            <div v-if="field.active === 'true'" class="mb-2">
                                <InputComponent
                                    :type="field.type"
                                    :name="field.name"
                                    v-model="field.value"
                                    :placeholder="field.name"
                                    :label="field.name"
                                    :errorMessage="formErrors[field.name]"
                                    :options="field.options"
                                    :constants="getConstants(field.name)"
                                    inputClass="w-full mt-1 text-sm border-gray-300 rounded-md py-1 px-2"
                                />
                            </div>
                        </template>

                        <div class="col-span-1 flex justify-center sm:col-span-2">
                            <PrimaryButton disabled class="text-sm py-1 px-3">
                                {{ $t('form.start_payment') }}
                            </PrimaryButton>
                        </div>

                        <div class="col-span-2 flex items-center justify-center w-full">
                            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_footer') }}</span></p>
                                    <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                                </div>
                                <input id="dropzone-file" type="file" class="hidden" />
                            </label>
                        </div>
                    </div>

                    <div id="fields" class="bg-white px-2 py-3 rounded-2xl shadow-lg">
                        <div v-if="form.configuration && form.configuration.fields && form.configuration.fields.length > 0">
                            <h3 class="mb-4 font-semibold text-gray-900">Fields</h3>
                            <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                                <li v-for="field in form.configuration.fields" :key="field.name" class="w-full border-b border-gray-200 rounded-t-lg">
                                    <div class="flex items-center ps-3">
                                        <input
                                            :id="'checkbox-' + field.name"
                                            type="checkbox"
                                            :disabled = "field.required === 'true'"
                                            :checked="field.active === 'true'"
                                            @change="toggleFieldActive(field)"
                                            class="w-4 h-4 text-orange-400 bg-gray-50 border-orange-500 rounded focus:ring-orange-500 focus:ring-2"
                                        >
                                        <label
                                            :for="'checkbox-' + field.name"
                                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                                        >
                                            {{ $t(`form.${field.name}`) }}
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div v-else>
                            <p class="text-gray-500 text-center">No fields available.</p>
                        </div>
                        <div class="flex justify-center pt-4">
                            <PrimaryButton @click="submit">
                                {{ $t('save') }}
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </main>
        </template>
    </AuthenticatedLayout>
</template>

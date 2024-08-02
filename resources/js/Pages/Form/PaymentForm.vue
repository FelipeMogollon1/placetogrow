<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import { ref} from "vue";
import { PhotoIcon, ArrowSmallLeftIcon} from "@heroicons/vue/24/outline/index.js";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const page = usePage();
const microsite = ref(page.props.microsite || {});
const formConfig = ref(page.props.microsite.form || {});
const documentTypes = ref(page.props.arrayConstants.documentTypes || {});
const currencyTypes =ref(page.props.arrayConstants.currencyTypes || {});
const currency = ref(page.props.microsite.currency || {});

const initialValues = {
    payer_name : "",
    payer_surname : "",
    payer_email : "",
    payer_document_type : "CC",
    payer_document : "",
    payer_phone : "",
    reference: "",
    description: "",
    currency: currency,
    amount: ""
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('payments.store'))
}
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <Head :title="$t('form.electronic_payments')" />
        <header class="bg-white shadow-md rounded-md w-full h-16 flex justify-between items-center p-6">
            <Link
                :href="route('Welcome')"
                class="flex items-center text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
            >
                <ArrowSmallLeftIcon class="w-6 h-6 mr-2 text-gray-600 hover:text-gray-500"/>
                {{$t('return')}}
            </Link>
            <ApplicationLogo/>
            <nav class="flex items-center space-x-4">
                <LanguageDropdown />
            </nav>
        </header>


           <main class="flex-grow flex items-center justify-center bg-gray-50">
            <div id="form" class="bg-white  p-4 m-4 rounded-2xl shadow-lg w-full max-w-4xl">

                 <div v-for="field in formConfig.configuration.head" :key="field.head" class="m-3" >
                    <div  class="col-span-2 flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_header') }}</span></p>
                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" />
                        </label>
                    </div>
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

                <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4  " >
                    <template v-for="field in formConfig.configuration.fields" :key="field.name">
                        <div v-if="field.active === 'true'" class="mb-2">

                            <div v-if="field.type === 'text' && field.name === 'name' && field.active === 'true'">
                                <InputLabel for="name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.payer_name"
                                    autofocus
                                    autocomplete="name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.payer_name" />
                            </div>

                            <div v-if="field.type === 'text' && field.name === 'surname' && field.active === 'true'">
                                <InputLabel for="surname" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.payer_surname"
                                    autofocus
                                    :autocomplete="field.name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.payer_surname" />
                            </div>

                            <div v-if="field.type === 'email' && field.name === 'email' && field.active === 'true'">
                                <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.payer_email"
                                    autofocus
                                    autocomplete="email"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.payer_email" />
                            </div>

                            <div v-if="field.type === 'select' && field.name === 'document_type' && field.active === 'true'">
                                <InputLabel for="name" :value="$t('microsites_table.document_type')" />
                                <select
                                    :name="field.name"
                                    :id="field.name"
                                    class="w-full mt-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                    v-model="form.payer_document_type"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(type, index) in documentTypes" :key="index" :value="type">{{ $t(`documentType.${type}`) }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.payer_document_type" />
                            </div>

                            <div v-if="field.type === 'text' && field.name === 'document' && field.active === 'true'">
                                <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.payer_document"
                                    autofocus
                                    :autocomplete="field.name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.payer_document" />
                            </div>

                            <div v-if="field.type === 'number' && field.name === 'mobile' && field.active === 'true'">
                                <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.payer_phone"
                                    autofocus
                                    :autocomplete="field.name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.payer_phone" />
                            </div>

                            <div v-if="field.type === 'text' && field.name === 'reference' && field.active === 'true'">
                                <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.reference"
                                    autofocus
                                    :autocomplete="field.name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.reference" />
                            </div>

                            <div v-if="field.type === 'text' && field.name === 'description' && field.active === 'true'">
                                <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.description"
                                    autofocus
                                    :autocomplete="field.name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <div v-if="field.type === 'select' && field.name === 'currency_type' && field.active === 'true'">
                                <InputLabel for="name" :value="$t('currency')" />
                                <select
                                    :name="field.name"
                                    :id="field.name"
                                    class="w-full mt-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                    v-model="form.currency"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(type, index) in currencyTypes" :key="index" :value="type">{{ $t(`currencies.${type}`) }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.currency" />
                            </div>

                            <div v-if="field.type === 'number' && field.name === 'amount' && field.active === 'true'">
                                <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                <TextInput
                                    :id="field.name"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.amount"
                                    autofocus
                                    :autocomplete="field.name"
                                    :placeholder="$t(`form.${field.name}`)"
                                />
                                <InputError class="mt-2" :message="form.errors.amount" />
                            </div>

                        </div>
                    </template>

                    <div class="col-span-1 flex justify-center sm:col-span-2">
                        <PrimaryButton class="text-sm py-1 px-3">
                            {{ $t('form.start_payment') }}
                        </PrimaryButton>
                    </div>
                </form>

                 <footer v-for="field in formConfig.configuration.footer" :key="field.head" class="m-3">
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
                </footer>

            </div>
        </main>

        <FooterIndex />
    </div>
</template>

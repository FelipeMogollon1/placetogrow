<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import {reactive, ref} from "vue";
import InputComponent from "@/Layouts/Organisms/InputComponent.vue";
import {ArrowSmallLeftIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";
import {route} from "ziggy-js";

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
    configuration: formConfig.configuration,
    footer: formConfig.configuration.footer
});

const formErrors = ref(form.errors);

function submitForm() {
    form.post(route('forms.store'), {
        onSuccess: () => {
            form.reset();
            alert('Microsite created successfully.');
        },
        onError: () => {
            alert('Failed to create microsite.');
        }
    });
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

        <form @submit.prevent="submitForm" method="POST" >
           <main class="flex-grow flex items-center justify-center bg-gray-50">
            <div id="form" class="bg-white grid grid-cols-1 md:grid-cols-2 gap-4 p-4 m-4 rounded-2xl shadow-lg w-full max-w-4xl">

                <template v-for="field in form.configuration.head" :key="field.head">
                    <div v-if="formConfig.configuration.footer !== 'null'" class="col-span-2 flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_header') }}</span></p>
                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" />
                        </label>
                    </div>
                </template>

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
                                v-model="name"
                                :placeholder="field.name"
                                :label="field.name"
                                :errorMessage="formErrors[field.name]"
                                :options="field.options"
                                :constants="getConstants(field.name)"
                                :autocomplete="field.name"
                                inputClass="w-full mt-1 text-sm border-gray-300 rounded-md py-1 px-2"
                            />
                        </div>
                    </template>

                    <div class="col-span-1 flex justify-center sm:col-span-2">
                        <PrimaryButton class="text-sm py-1 px-3">
                            {{ $t('form.start_payment') }}
                        </PrimaryButton>
                    </div>

                <template v-for="field in form.configuration.footer" :key="field.head">
                    <div class="col-span-2 flex items-center justify-center w-full">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 border-gray-600 hover:border-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_footer') }}</span></p>
                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" />
                        </label>
                    </div>
                </template>

            </div>
        </main>
        </form>
        <FooterIndex />
    </div>
</template>

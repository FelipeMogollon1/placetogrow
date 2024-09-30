<script setup>
import {ref, reactive, watch, computed} from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import { route } from 'ziggy-js';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputComponent from '@/Layouts/Organisms/InputComponent.vue';
import SubscriptionView from "@/Layouts/Organisms/SubscriptionView.vue";
import Index from "@/Pages/SubscriptionPlans/Index.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";

const page = usePage();
const microsite = ref(page.props.microsite || {});
const formConfig = reactive(page.props.microsite.form || {});
const periods = ref(page.props.arrayConstants.periods || {});
const subscriptionPlans = ref(page.props.subscriptionPlans || {});

watch(() => page.props.subscriptionPlans, (newSubscriptionPlans) => {
    subscriptionPlans.value = newSubscriptionPlans;
});

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

const initialValues = {
    id: formConfig.id,
    header: null,
    footer: null,
    color: formConfig.color ?? "",
    additional_info: formConfig.additional_info ?? "",
    expiration_additional_info : formConfig.expiration_additional_info ?? "",
    colorDefault: formConfig.colorDefault ?? "",
    configuration: formConfig.configuration
}

const form = useForm(initialValues)
const formErrors = ref(form.errors);

const toggleFieldActive = (field) => {
    field.active = field.active === 'true' ? 'false' : 'true';
};

const selectedImage = ref(null);

const handleHeaderFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            selectedImage.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }
    const filesHeader = e.target.files

    if(filesHeader.length){
        form.header = filesHeader[0]
    }

};

const selectedFooterImage = ref(null);

const handleFooterFileChange = (e) => {

    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            selectedFooterImage.value = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    const filesFooter = e.target.files
    if(filesFooter.length){
        form.footer = filesFooter[0]
    }
};

watch(formConfig, (newConfig) => {
    form.configuration = newConfig.configuration;
}, { deep: true });

const colorOptions = {
    white: 'white',
    yellow: 'yellow',
    orange: 'orange',
    green: 'green',
    lime: 'lime',
    fuchsia: 'fuchsia',
    pink: 'pink',
    blue: 'blue',
    red: 'red',
    violet: 'violet',
    cyan : 'cyan',
    gray: 'gray'
};

const selectedColor = ref(form.color);

watch(selectedColor, (newColor) => {
    form.color = newColor;
});

const submit = () => {
    form.post(route('forms.custom_update', form.id))
};


const selectedColorDefault = ref(form.colorDefault);

watch(selectedColorDefault, (newColor) => {
    form.colorDefault = newColor;
});


const liveAdditionalInfo = ref(form.additional_info);
const liveExpirationInfo = ref(form.expiration_additional_info);

watch(() => liveAdditionalInfo, (newValue) => {
    form.additional_info = newValue;
});

watch(() => liveExpirationInfo, (newValue) => {
    form.expiration_additional_info = newValue;
});


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
        </template>

        <main id="details" class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-lg">
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
        </main>
        <main v-if="microsite.microsite_type === 'subscription' " id="subscriptions" class="py-5">

            <index :periods="periods" :microsite="microsite" :subscriptionPlans="subscriptionPlans" :currency="constants.currencyTypes.value"/>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('subscription.Preview') }}</h2>

            <div class="flex justify-center items-center">
                <div id="form" class="border border-1 container bg-white grid grid-cols-2 md:grid-cols-1 gap-4 sm:grid-cols-1 m-4 rounded-2xl shadow-lg">
                    <SubscriptionView  :color="selectedColor"
                                       :microsite="microsite"
                                       :subscriptionPlans="subscriptionPlans"
                                       :fields="form.configuration.fields"
                                       :documentTypes="page.props.arrayConstants.documentTypes"
                                       :disableSubmit="true"
                                       :forms="formConfig"
                    />
                </div>

                <div id="fields" class="bg-white px-2 py-3 rounded-2xl shadow-lg">
                    <div v-if="form.configuration && form.configuration.fields && form.configuration.fields.length > 0">
                        <h3 class="mb-4 font-semibold text-gray-900">{{ $t('form.fields') }}</h3>
                        <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                            <li v-for="field in form.configuration.fields" :key="field.name" class="w-full border-b border-gray-200 rounded-t-lg">
                                <div class="flex items-center ps-3">
                                    <input
                                        :id="'checkbox-' + field.name"
                                        type="checkbox"
                                        :disabled = "field.required === 'true'"
                                        :checked="field.active === 'true'"
                                        @change="toggleFieldActive(field)"
                                        :class="{
                                            'w-4 h-4 text-orange-400 bg-gray-50 border-orange-500 rounded focus:ring-orange-500 focus:ring-2': field.required !== 'true',
                                            'w-4 h-4 text-gray-200 bg-gray-50 border-gray-500 rounded focus:ring-gray-500 focus:ring-2': field.required === 'true'
                                        }"
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

                        <h2 class="text-lg my-4 font-semibold text-gray-900">{{ $t('subscription.Default') }}</h2>
                        <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                            <li  class="w-full border-b border-gray-200 rounded-t-lg">
                                <select
                                    id="color-select"
                                    v-model="selectedColor"
                                    class="w-full border border-gray-50 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(label, value) in colorOptions" :key="value" :value="value">
                                        {{ $t(`form.${label}`) }}
                                    </option>
                                </select>
                            </li>
                        </ul>

                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 my-4">{{ $t('subscription.additional_info') }}</h2>
                            <TextInput
                                id="additional_info"
                                type="text"
                                class="mt-1 block w-full border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500"
                                v-model="form.additional_info"
                                :placeholder="$t('subscription.additional_info')"
                            />
                            <InputError class="mt-2 text-red-600" :message="form.errors.additional_info" />

                            <h2 class="text-lg font-semibold text-gray-900 my-4">{{ $t('subscription.expiration_additional_info') }}</h2>
                            <TextInput
                                id="expiration_additional_info"
                                type="text"
                                class="mt-1 block w-full border border-gray-300 rounded-md focus:ring-gray-500 focus:border-gray-500"
                                v-model="form.expiration_additional_info"
                                :placeholder="$t('subscription.expiration_additional_info')"
                            />
                            <InputError class="mt-2 text-red-600" :message="form.errors.expiration_additional_info" />
                        </div>

                    </div>

                    <div class="flex justify-center pt-4">
                        <PrimaryButton @click="submit">
                            {{ $t('save') }}
                        </PrimaryButton>
                    </div>
                </div>

            </div>
        </main>



        <main v-else id="form">
            <div class="flex justify-between items-center pt-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('form.label') }}</h2>

            </div>

            <div class="flex justify-center items-center">
                <div id="form" class="container bg-white grid grid-cols-2 md:grid-cols-2 gap-4 sm:grid-cols-1 p-4 m-4 rounded-2xl shadow-lg">

                    <div class="col-span-2 flex items-center justify-center w-full h-40 border-2 border-orange-300 border-dashed rounded-lg cursor-pointer bg-orange-50 hover:bg-orange-50 hover:border-orange-500">
                        <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-full">
                            <div v-if="selectedImage" class="flex items-center justify-center w-full h-full">
                                <img :src="selectedImage" class="w-full h-full object-cover rounded-lg" :alt="$t('form.select_header')"/>
                            </div>

                            <div v-else-if="microsite.form.header !== null" class="flex items-center justify-center w-full h-full">
                                <img :src="`/storage/${microsite.form.header}`" class="w-full h-full object-cover rounded-lg" :alt="$t('form.select_header')"/>
                            </div>

                            <div v-else class="flex flex-col items-center justify-center w-full h-full">
                                <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_header') }}</span></p>
                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                            <input id="dropzone-file" type="file" class="hidden" @change="handleHeaderFileChange" />
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
                        <template v-if="field.active === 'true' && field.name !== 'currency_type' || field.active === 'true' && field.name === 'currency_type' && microsite.currency === 'BOTH' " class="mb-2">

                            <InputComponent
                                :type="field.type"
                                :name="field.name"
                                v-model="field.value"
                                :placeholder="field.name"
                                :label="field.name"
                                :errorMessage="formErrors[field.name]"
                                :options="field.options"
                                :constants="getConstants(field.name)"
                                :currency="microsite.currency"
                                inputClass="w-full mt-1 text-sm border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md py-1 px-2"
                            />
                        </template>
                    </template>

                    <div class="col-span-1 flex justify-center sm:col-span-2">
                        <PrimaryButton disabled class="text-sm py-1 px-3" :color="selectedColor">
                            {{ $t('form.start_payment') }}
                        </PrimaryButton>
                    </div>

                    <div class="col-span-2 flex items-center justify-center w-full">
                        <label for="dropzone-footer" class="flex flex-col items-center justify-center w-full h-40 border-2 border-orange-300 border-dashed rounded-lg cursor-pointer bg-orange-50 hover:bg-orange-50 hover:border-orange-500">
                            <div v-if="selectedFooterImage" class="flex items-center justify-center w-full h-full">
                                <img :src="selectedFooterImage" class="w-full h-full object-cover rounded-lg" :alt="$t('form.select_footer')"/>
                            </div>

                            <div v-else-if="microsite.form.footer !== null" class="flex items-center justify-center w-full h-full">
                                <img :src="`/storage/${microsite.form.footer}`" class="w-full h-full object-cover rounded-lg" :alt="$t('form.select_header')"/>
                            </div>

                            <div v-else class="flex flex-col items-center justify-center">
                                <PhotoIcon class="w-12 h-12 text-gray-400"/>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">{{ $t('form.select_footer') }}</span></p>
                                <p class="text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 800x400px)</p>
                            </div>
                            <input id="dropzone-footer" type="file" class="hidden" @change="handleFooterFileChange" />
                        </label>
                    </div>

                </div>

                <div id="fields" class="bg-white px-2 py-3 rounded-2xl shadow-lg">
                    <div v-if="form.configuration && form.configuration.fields && form.configuration.fields.length > 0">
                        <h3 class="mb-4 font-semibold text-gray-900">{{ $t('form.fields') }}</h3>
                        <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                            <li v-for="field in form.configuration.fields" :key="field.name" class="w-full border-b border-gray-200 rounded-t-lg">
                                <div class="flex items-center ps-3">
                                    <input
                                        :id="'checkbox-' + field.name"
                                        type="checkbox"
                                        :disabled = "field.required === 'true'"
                                        :checked="field.active === 'true'"
                                        @change="toggleFieldActive(field)"
                                        :class="{
                                            'w-4 h-4 text-orange-400 bg-gray-50 border-orange-500 rounded focus:ring-orange-500 focus:ring-2': field.required !== 'true',
                                            'w-4 h-4 text-gray-200 bg-gray-50 border-gray-500 rounded focus:ring-gray-500 focus:ring-2': field.required === 'true'
                                        }"
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

                        <h3 class="my-4 font-semibold text-gray-900">{{$t('form.button_color')}}</h3>
                        <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                            <li  class="w-full border-b border-gray-200 rounded-t-lg">
                                <select
                                    id="color-select"
                                    v-model="selectedColor"
                                    class="w-full border border-gray-50 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(label, value) in colorOptions" :key="value" :value="value">
                                        {{ $t(`form.${label}`) }}
                                    </option>
                                </select>
                            </li>
                        </ul>

                    </div>

                    <div class="flex justify-center pt-4">
                        <PrimaryButton @click="submit">
                            {{ $t('save') }}
                        </PrimaryButton>
                    </div>
                </div>
            </div>
        </main>

    </AuthenticatedLayout>
</template>

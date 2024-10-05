<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import { ArrowSmallLeftIcon} from "@heroicons/vue/24/outline/index.js";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import SubscriptionView from "@/Layouts/Organisms/SubscriptionView.vue";
import FlashMessages from '@/Layouts/Molecules/FlashMessages.vue';


const page = usePage();
const microsite = ref(page.props.microsite || {});
const formConfig = ref(page.props.microsite.form || {});
const documentTypes = ref(page.props.arrayConstants.documentTypes || {});
const currencyTypes =ref(page.props.arrayConstants.currencyTypes || {});
const currency = ref(page.props.microsite.currency || {});
const microsite_id = ref(page.props.microsite.id || {});
const subscriptionPlans = ref(page.props.subscriptionPlans || {});

const initialValues = {
    payer_name : "",
    payer_surname : "",
    payer_email : "",
    payer_document_type : "CC",
    payer_document : "",
    payer_phone : "",
    payer_company: "",
    reference: "",
    company:"",
    description: "",
    currency: currency.value !== "BOTH" ? currency.value : "COP",
    amount: "",
    microsite_id : microsite_id,
    color: formConfig.color
}

const form = useForm(initialValues)

const colorOptions = {
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

const submit = () => {
    form.amount = formattedAmount.value.replace(/,/g, '');
    form.post(route('payments.store'))
}

const patterns = {
    CC: /^[1-9][0-9]{3,9}$/,
    CE: /^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/,
    TI: /^[1-9][0-9]{4,11}$/,
    NIT: /^[1-9]\d{6,9}$/,
};

const documentErrorMessage = ref('');
const validateDocument = () => {
    const docType = form.payer_document_type;
    const docValue = form.payer_document;

    if (patterns[docType]) {
        const isValid = patterns[docType].test(docValue);
        documentErrorMessage.value = isValid ? '' : 'El número de documento no es válido para el tipo seleccionado.';
    } else {
        documentErrorMessage.value = 'Tipo de documento no válido.';
    }
};

watch(() => form.payer_document_type, validateDocument);
watch(() => form.payer_document, validateDocument);

const currencySymbol = ref(getCurrencySymbol(form.currency));
const formattedAmount = ref(form.amount);
const errorMessage = ref('');

function getCurrencySymbol(currency) {
    return currency === 'COP' ? 'COP $' : 'USD $';
}

const handleInput = () => {
    let numericValue = formattedAmount.value.replace(/[^0-9.]/g, '');

    const maxDigits = getMaxDigits();
    if (numericValue.replace(/,/g, '').length > maxDigits) {
        numericValue = numericValue.slice(0, maxDigits);
    }

    const parts = numericValue.split('.');
    if (parts.length > 2) {
        numericValue = `${parts[0]}.${parts[1]}`;
    }

    const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    const decimalPart = parts[1] ? `.${parts[1].slice(0, 2)}` : '';

    formattedAmount.value = `${integerPart}${decimalPart}`;
    form.amount = formattedAmount.value;
};

const getMaxDigits = () => {
    if (currencySymbol.value === 'USD $') {
        return 5;
    } else if (currencySymbol.value === 'COP $') {
        return 9;
    }
    return Infinity;
};

const validateAmount = () => {
    let numericValue = formattedAmount.value.replace(/[^0-9.]/g, '');
    numericValue = parseFloat(numericValue);

    if (currencySymbol.value === 'USD $' && numericValue > 99999) {
        formattedAmount.value = '99,999';
        form.amount = formattedAmount.value;
        errorMessage.value = 'El monto en USD no puede ser mayor a 99,999.';
    } else if (currencySymbol.value === 'COP $' && numericValue > 999999999) {
        formattedAmount.value = '999,999,999';
        form.amount = formattedAmount.value;
        errorMessage.value = 'El monto en COP no puede ser mayor a 999,999,999.';
    } else if (currencySymbol.value !== '' && numericValue <= 0){
        errorMessage.value = 'El monto ingresado no es valido';
    }
};

watch(() => form.amount, (newValue) => {
    formattedAmount.value = newValue;
});

watch(() => form.currency, (newCurrency) => {
    currencySymbol.value = getCurrencySymbol(newCurrency);
});

</script>

<template>
    <div class="flex flex-col min-h-screen">
        <Head :title="microsite.name" />
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
        <flash-messages />
        <main v-if="microsite.microsite_type === 'subscription'">

            <SubscriptionView  :color="colorOptions[formConfig.color]"
                               :microsite="microsite"
                               :subscriptionPlans="subscriptionPlans"
                               :fields="formConfig.configuration.fields"
                               :documentTypes="documentTypes"
                               :forms="formConfig"
            />

        </main>


           <main v-else class="flex items-center justify-center bg-white my-6">
            <div id="form" class="bg-white  rounded-2xl shadow-2xl border border-gray-200 w-full max-w-4xl">

                <div class="col-span-2 flex items-center justify-center w-full h-40">

                     <div v-if="microsite.form.header !== null" class="flex items-center justify-center w-full h-full">
                         <img :src="`/storage/${microsite.form.header}`" class="w-full h-full object-cover rounded-t-lg" :alt="$t('form.select_header')"/>
                     </div>

                    <p v-else class="flex items-center justify-center text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold tracking-tight text-gray-700">
                        <img v-if="microsite.logo" class="pe-10 rounded-lg h-24 object-contain" :src="`/storage/${microsite.logo}`" alt="Logo">
                        <span class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-gray-600 tracking-wide">
                            {{ microsite.name }}
                        </span>
                    </p>
                </div>

                <div class="m-5">
                    <div class="col-span-2 flex text-gray-600">
                        <div class="text-2xl font-semibold">
                            {{ $t('form.electronic_payments') }}
                        </div>
                    </div>
                    <div class="col-span-2 flex pt-1 pb-6 text-gray-500">
                        <div v-if="formConfig.configuration.additional_information === '' || formConfig.configuration.additional_information === null " class="text-md pb-2">
                            {{ $t('payment.additional_data') }}
                        </div>
                        <div v-else class="text-md pb-2">
                            {{formConfig.configuration.additional_information}}
                        </div>
                    </div>

                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4" >
                        <template v-for="field in formConfig.configuration.fields" :key="field.name">
                            <template v-if="field.active === 'true'" class="mb-2">

                                <div v-if="field.type === 'text' && field.name === 'name'">
                                    <InputLabel for="name" :value="'*' +  $t(`form.${field.name}`)" />
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

                                <div v-if="field.type === 'text' && field.name === 'surname'">
                                    <InputLabel for="surname" :value="'*' +  $t(`form.${field.name}`)" />
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

                                <div v-if="field.type === 'select' && field.name === 'document_type'">
                                    <InputLabel for="name" :value="'*' +  $t('microsites_table.document_type')" />
                                    <select
                                        :name="field.name"
                                        :id="field.name"
                                        class="w-full mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm"
                                        v-model="form.payer_document_type"
                                    >
                                        <option value="" disabled>{{ $t('select') }}</option>
                                        <option v-for="(type, index) in documentTypes" :key="index" :value="type">{{ $t(`documentType.${type}`) }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.payer_document_type" />
                                </div>

                                <div v-if="field.type === 'number' && field.name === 'document'">
                                    <InputLabel :for="field.name" :value="'*' +  $t(`form.${field.name}`)" />
                                    <TextInput
                                        :id="field.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        @input="validateDocument"
                                        v-model="form.payer_document"
                                        autofocus
                                        :autocomplete="field.name"
                                        :placeholder="$t(`form.${field.name}`)"
                                    />
                                    <InputError class="mt-2" :message="documentErrorMessage" />
                                    <InputError class="mt-2" :message="form.errors.payer_document" />
                                </div>

                                <div v-if="field.type === 'number' && field.name === 'mobile' ">
                                    <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                    <TextInput
                                        :id="field.name"
                                        type="number"
                                        class="mt-1 block w-full"
                                        v-model="form.payer_phone"
                                        autofocus
                                        :autocomplete="field.name"
                                        :placeholder="$t(`form.${field.name}`)"
                                        pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                                    />
                                    <InputError class="mt-2" :message="form.errors.payer_phone" />
                                </div>

                                <div v-if="field.type === 'text' && field.name === 'reference' ">
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

                                <div v-if="field.type === 'text' && field.name === 'company' ">
                                    <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                                    <TextInput
                                        :id="field.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.payer_company"
                                        autofocus
                                        :autocomplete="field.name"
                                        :placeholder="$t(`form.${field.name}`)"
                                    />
                                    <InputError class="mt-2" :message="form.errors.payer_company" />
                                </div>

                                <div v-if="field.type === 'text' && field.name === 'description'">
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

                                <div v-if="field.type === 'select' && field.name === 'currency_type' && microsite.currency === 'BOTH' ">
                                    <InputLabel for="name" :value="'*' +  $t('currency')" />
                                    <select
                                        :name="field.name"
                                        :id="field.name"
                                        class="w-full mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm"
                                        v-model="form.currency"
                                    >
                                        <option value="" disabled>{{ $t('select') }}</option>
                                        <option v-for="(type, index) in currencyTypes.filter(constant => constant !== 'BOTH')" :key="index" :value="type">
                                            {{ $t(`currencies.${type}`) }}
                                        </option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.currency" />
                                </div>

                                <div v-if="field.type === 'number' && field.name === 'amount' ">
                                    <InputLabel :for="field.name" :value="'*' +  `${$t(`form.${field.name}`)}  ${$t(`currencies.${form.currency}`)}`" />

                                    <div class="flex flex-col">
                                        <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                           <span class="text-gray-500">{{ currencySymbol }}</span>
                                        </span>

                                            <input
                                                :id="field.name"
                                                type="text"
                                                step="0.01"
                                                v-model="formattedAmount"
                                                @input="handleInput"
                                                @blur="validateAmount"
                                                autofocus
                                                :autocomplete="field.name"
                                                placeholder="0.00"
                                                class="pl-8 pr-4 py-2 border rounded-lg focus:outline-none border-gray-300 focus:border-gray-500 focus:ring-gray-500 w-full text-right"
                                            />

                                        </div>
                                    </div>

                                    <InputError class="mt-2" :message="errorMessage" />
                                    <InputError class="mt-2" :message="form.errors.amount" />
                                </div>

                                <div v-if="field.type === 'email' && field.name === 'email' ">
                                    <InputLabel :for="field.name" :value="'*' +  $t(`form.${field.name}`)" />
                                    <TextInput
                                        :id="field.name"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="form.payer_email"
                                        autofocus
                                        autocomplete="email"
                                        :placeholder="$t(`form.${field.name}`)"
                                    />
                                    <InputError class="mt-2" :message="form.errors.payer_email" />
                                </div>

                            </template>

                        </template>

                        <div class="col-span-1 flex justify-center sm:col-span-2">
                            <PrimaryButton class="text-sm py-1 px-3" :color="colorOptions[formConfig.color]">
                                {{ $t('form.start_payment') }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>

                 <footer v-if="formConfig.footer !== null ">
                     <div class="col-span-2 flex items-center justify-center w-full h-40 rounded-lg">
                         <div v-if="true" class="flex items-center justify-center w-full h-full">
                             <img :src="`/storage/${formConfig.footer}`" class="w-full h-full object-cover rounded-b-lg" :alt="$t('form.select_footer')"/>
                         </div>

                     </div>
                </footer>

            </div>
        </main>

        <FooterIndex />
    </div>
</template>

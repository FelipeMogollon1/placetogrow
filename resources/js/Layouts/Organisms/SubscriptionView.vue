<script setup>
import {ref, computed, watch} from 'vue';
import {CheckBadgeIcon} from "@heroicons/vue/24/outline/index.js";
import { useForm } from '@inertiajs/vue3';
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { FaceFrownIcon } from "@heroicons/vue/24/outline/index.js";
import {route} from "ziggy-js";

const selectedCurrency = ref('USD');

const showForm = ref(false);
const selectedSubscription = ref(null);

const selectSubscription = (subscription) => {
    selectedSubscription.value = subscription;
    showForm.value = true;
};

const submitForm = () => {
    form.post(route('subscriptions.store'));
};

const props = defineProps({
    color: {
        type: String,
        default: 'white'
    },
    microsite: {
        type: Object,
        default: null
    },
    subscriptionPlans:{
        type: Object,
        default: () => []
    },
    fields: {
        type: Object,
        default: () => []
    },
    documentTypes:{
        type: Object,
        default: () => []
    },
    disableSubmit: {
        type: Boolean,
        default: false
    }
});

const initialValues = {
    name : "",
    surname : "",
    email : "",
    document_type : "CC",
    document : "",
    mobile : "",
    company: "",
    reference: "",
    description: "",
    amount: "",
    microsite_id : props.microsite.id,
    subscription_plan_id: selectedSubscription?.id
}

watch(selectedSubscription, (newValue) => {
    form.subscription_plan_id = newValue?.id || null;
});

const form = useForm(initialValues)

const colorOptions = {
    white: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-400',
        backgroundFrom: 'from-gray-50',
        backgroundTo: 'to-gray-500',
        cardFrom: 'from-white hover:from-gray-50',
        cardTo: 'to-gray-50 hover:to-gray-100',
        button: 'bg-gray-400 hover:bg-gray-500',
        buttonText: 'text-gray-500 hover:text-gray-600',
    },
    yellow: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-yellow-50',
        backgroundTo: 'to-yellow-700',
        cardFrom: 'from-yellow-500 hover:from-yellow-600',
        cardTo: 'to-yellow-600 hover:to-yellow-700',
        button: 'bg-yellow-500 hover:bg-yellow-600',
        buttonText: 'text-yellow-500 hover:text-yellow-600',
    },
    orange: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-orange-50',
        backgroundTo: 'to-orange-700',
        cardFrom: 'from-orange-400 hover:from-orange-500',
        cardTo: 'to-orange-500 hover:to-orange-600',
        button: 'bg-orange-600 hover:bg-orange-700',
        buttonText: 'text-orange-600 hover:text-orange-700',
    },
    green: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-green-50',
        backgroundTo: 'to-green-700',
        cardFrom: 'from-green-500 hover:from-green-600',
        cardTo: 'to-green-600 hover:to-green-700',
        button: 'bg-green-600 hover:bg-green-700',
        buttonText: 'text-green-600 hover:text-green-700',
    },
    lime: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-lime-50',
        backgroundTo: 'to-lime-700',
        cardFrom: 'from-lime-500 hover:from-lime-600',
        cardTo: 'to-lime-600 hover:to-lime-700',
        button: 'bg-lime-600 hover:bg-lime-700',
        buttonText: 'text-lime-600 hover:text-lime-700',
    },
    fuchsia: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-fuchsia-50',
        backgroundTo: 'to-fuchsia-700',
        cardFrom: 'from-fuchsia-300 hover:from-fuchsia-500',
        cardTo: 'to-fuchsia-500 hover:to-fuchsia-700',
        button: 'bg-fuchsia-600 hover:bg-fuchsia-700',
        buttonText: 'text-fuchsia-600 hover:text-fuchsia-700',
    },
    pink: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-pink-50',
        backgroundTo: 'to-pink-700',
        cardFrom: 'from-pink-400 hover:from-pink-500',
        cardTo: 'to-pink-500 hover:to-pink-600',
        button: 'bg-pink-600 hover:bg-pink-700',
        buttonText: 'text-pink-600 hover:text-pink-700',
    },
    blue: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-blue-50',
        backgroundTo: 'to-blue-700',
        cardFrom: 'from-blue-400 hover:from-blue-500',
        cardTo: 'to-blue-500 hover:to-blue-600',
        button: 'bg-blue-600 hover:bg-blue-700',
        buttonText: 'text-blue-600 hover:text-blue-700',
    },
    red: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-red-50',
        backgroundTo: 'to-red-700',
        cardFrom: 'from-red-300 hover:from-red-400',
        cardTo: 'to-red-400 hover:to-red-500',
        button: 'bg-red-600 hover:bg-red-700',
        buttonText: 'text-red-600 hover:text-red-700',
    },
    violet: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-violet-50',
        backgroundTo: 'to-violet-700',
        cardFrom: 'from-violet-300 hover:from-violet-400',
        cardTo: 'to-violet-400 hover:to-violet-500',
        button: 'bg-violet-600 hover:bg-violet-700',
        buttonText: 'text-violet-600 hover:text-violet-700',
    },
    cyan: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-cyan-50',
        backgroundTo: 'to-cyan-700',
        cardFrom: 'from-cyan-400 hover:from-cyan-500',
        cardTo: 'to-cyan-600 hover:to-cyan-700',
        button: 'bg-cyan-600 hover:bg-cyan-700',
        buttonText: 'text-cyan-600 hover:text-cyan-700',
    },
    gray: {
        primaryText: 'text-gray-900',
        secondaryText: 'text-gray-50',
        backgroundFrom: 'from-gray-50',
        backgroundTo: 'to-gray-700',
        cardFrom: 'from-gray-400 hover:from-gray-500',
        cardTo: 'to-gray-500 hover:to-gray-600',
        button: 'bg-gray-600 hover:bg-gray-700',
        buttonText: 'text-gray-600 hover:text-gray-700',
    },
};

const colorClasses = computed(() => {
    return colorOptions[props.color] || colorOptions['white'];
});
const gridClasses = computed(() => {
    const length = props.subscriptionPlans.data.length;

    switch (length) {
        case 1:
            return 'grid-cols-1';
        case 2:
            return 'grid-cols-1 sm:grid-cols-2';
        case 3:
            return 'sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
        case 4:
            return 'grid-cols-2 sm:grid-cols-2';
        default:
            return 'sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
    }
});

const patterns = {
    CC: /^[1-9][0-9]{3,9}$/,
    CE: /^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/,
    TI: /^[1-9][0-9]{4,11}$/,
    NIT: /^[1-9]\d{6,9}$/,
};

const documentErrorMessage = ref('');
const validateDocument = () => {
    const docType = form.document_type;
    const docValue = form.document;

    if (patterns[docType]) {
        const isValid = patterns[docType].test(docValue);
        documentErrorMessage.value = isValid ? '' : 'El número de documento no es válido para el tipo seleccionado.';
    } else {
        documentErrorMessage.value = 'Tipo de documento no válido.';
    }
};

watch(() => form.document_type, validateDocument);
watch(() => form.document, validateDocument);

</script>

<template>
    <div :class="['bg-gradient-to-b min-h-screen flex flex-col items-center justify-center p-6', colorClasses.backgroundFrom, colorClasses.backgroundTo]">
       <div v-if="microsite.logo" class="flex lg:justify-center lg:col-start-2">
           <div class="group flex items-center rounded-xl mb-6">
               <img  class="rounded-xl w-12 object-contain mr-2" :src="`/storage/${microsite.logo}`" alt="Logo">
               <h1 :class="['m-1 text-4xl group-hover:text-gray-500', colorClasses.primaryText]">{{microsite.name}}</h1>
           </div>
       </div>

        <div v-if="subscriptionPlans.data && subscriptionPlans.data.length > 0">
            <h1 :class="['text-3xl font-bold', colorClasses.primaryText]">
                {{ $t('subscription.chooseYourPlan') }}
            </h1>
        </div>

        <div v-if="subscriptionPlans.data && subscriptionPlans.data.length > 0">


            <p class="text-center mb-6" :class="colorClasses.primaryText">
                {{ subscriptionPlans.data[0].additional_info || '' }}
            </p>

        </div>

        <div v-else class="flex flex-col items-center mt-6">
            <FaceFrownIcon class="w-12 text-gray-900 mb-4" />
            <h2 class="text-2xl text-gray-700 text-center">
                {{ $t('subscription.noSubscriptions') }}
            </h2>
        </div>


        <div v-if="!showForm" class="grid gap-6 w-full max-w-7xl" :class="gridClasses">
            <div
                v-for="(subscription, index) in subscriptionPlans.data"
                :key="index"
                @click="selectSubscription(subscription)"
                :class="[
                    'shadow-xl bg-gradient-to-r rounded-lg p-6 w-full text-center transition-transform duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl cursor-pointer',
                    colorClasses.cardFrom,
                    colorClasses.cardTo,
                    colorClasses.primaryText,
                ]"
            >
                <h2 :class="['lg:text-3xl md:text-3xl sm:text-4xl font-bold mb-2', colorClasses.primaryText]">{{ subscription.name }}</h2>
                <p :class="['lg:text-4xl md:text-3xl sm:text-4xl font-bold mb-4', colorClasses.secondaryText]">
                    {{ subscription.expiration_time }}x ${{ subscription.amount}} {{ subscription.currency}} / {{ $t(`subscription.${subscription.subscription_period}`) }}</p>
                <p class="mb-4 lg:text-2xl md:text-xl sm:text-3xl">{{ $t('subscription.totalPrice') }} ${{ (subscription.expiration_time * subscription.amount).toFixed(2) }} {{ subscription.currency}}</p>
                <div class="flex justify-center">
                    <ul class="grid grid-cols-1 gap-1">
                        <li
                            v-for="(feature, i) in JSON.parse(subscription.description)"
                            :key="i"
                            :class="['flex items-center lg:text-md md:text-sm sm:text-2xl', colorClasses.primaryText]"
                        >
                            <CheckBadgeIcon :class="['w-6 h-6 mr-2 flex-shrink-0 text-bold', colorClasses.secondaryText]" />
                            <span class="flex-shrink-0 rounded-2xl">{{ feature }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div v-else class="bg-white p-6 rounded-lg shadow-xl w-full max-w-xl mx-auto">
            <h2 class="text-2xl font-bold mb-4">{{ $t('subscription.subscriptionForm') }}</h2>

            <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" >
                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 font-bold mb-2">{{ $t('subscription.selectedSubscription') }}:</label>
                    <div class="bg-gray-100 p-2 rounded">{{ selectedSubscription?.name }}</div>
                </div>

                <template v-for="field in fields" :key="field.name">

                    <template v-if="field.active === 'true'" class="mb-2">

                        <div v-if="field.type === 'text' && field.name === 'name'">
                            <InputLabel for="name" :value="'*' +  $t(`form.${field.name}`)" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autofocus
                                autocomplete="name"
                                :placeholder="$t(`form.${field.name}`)"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div v-if="field.type === 'text' && field.name === 'surname'">
                            <InputLabel for="surname" :value="'*' +  $t(`form.${field.name}`)" />
                            <TextInput
                                :id="field.name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.surname"
                                autofocus
                                :autocomplete="field.name"
                                :placeholder="$t(`form.${field.name}`)"
                            />
                            <InputError class="mt-2" :message="form.errors.surname" />
                        </div>

                        <div v-if="field.type === 'select' && field.name === 'document_type'">
                            <InputLabel for="name" :value="'*' +  $t('microsites_table.document_type')" />
                            <select
                                :name="field.name"
                                :id="field.name"
                                class="w-full mt-1 border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-md shadow-sm"
                                v-model="form.document_type"
                            >
                                <option value="" disabled>{{ $t('select') }}</option>
                                <option v-for="(type, index) in documentTypes" :key="index" :value="type">{{ $t(`documentType.${type}`) }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.document_type" />
                        </div>

                        <div v-if="field.type === 'number' && field.name === 'document'">
                            <InputLabel :for="field.name" :value="'*' +  $t(`form.${field.name}`)" />
                            <TextInput
                                :id="field.name"
                                type="text"
                                class="mt-1 block w-full"
                                @input="validateDocument"
                                v-model="form.document"
                                autofocus
                                :autocomplete="field.name"
                                :placeholder="$t(`form.${field.name}`)"
                            />
                            <InputError class="mt-2" :message="documentErrorMessage" />
                            <InputError class="mt-2" :message="form.errors.document" />
                        </div>

                        <div v-if="field.type === 'number' && field.name === 'mobile' ">
                            <InputLabel :for="field.name" :value="$t(`form.${field.name}`)" />
                            <TextInput
                                :id="field.name"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.mobile"
                                autofocus
                                :autocomplete="field.name"
                                :placeholder="$t(`form.${field.name}`)"
                                pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                            />
                            <InputError class="mt-2" :message="form.errors.mobile" />
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
                                v-model="form.company"
                                autofocus
                                :autocomplete="field.name"
                                :placeholder="$t(`form.${field.name}`)"
                            />
                            <InputError class="mt-2" :message="form.errors.company" />
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


                        <div v-if="field.type === 'email' && field.name === 'email' ">
                            <InputLabel :for="field.name" :value="'*' +  $t(`form.${field.name}`)" />
                            <TextInput
                                :id="field.name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                autofocus
                                autocomplete="email"
                                :placeholder="$t(`form.${field.name}`)"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                    </template>

                </template>

                <div class="flex items-center justify-between col-span-2">
                    <button
                        type="submit"
                        :class="[
                            'text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline border',
                            colorClasses.button,
                            { 'opacity-50 cursor-not-allowed': props.disableSubmit }
                        ]"
                        :disabled="props.disableSubmit"
                    >
                        {{ $t('subscription.send') }}
                    </button>
                    <button
                        type="button"
                        @click="showForm = false"
                        :class="[
                            'font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline',
                            colorClasses.buttonText
                        ]"
                    >
                        {{ $t('subscription.return') }}
                    </button>
                </div>
            </form>
        </div>

        <div v-if="subscriptionPlans.data && subscriptionPlans.data.length > 0">
            <p v-if="!showForm" :class="['text-sm text-center mt-6 text-gray-50']">
                {{ subscriptionPlans.data[0].expiration_additional_info || '' }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import {CheckBadgeIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import { useForm } from '@inertiajs/vue3';

const showForm = ref(false);
const selectedSubscription = ref(null);
const form = useForm({ reference: '', email: '' });

const subscriptions = ref([
    {
        title: 'Básico con anuncios',
        price: '12x $16.900,00 /mes',
        description: 'Precio total anual $202.800,00',
        features: ['2 dispositivos a la vez', 'Resolución Full HD'],
    },
    {
        title: 'Estándar',
        price: '12x $19.900,00 /mes',
        description: 'Precio total anual $238.800,00',
        features: ['2 dispositivos a la vez', 'Resolución Full HD', '30 descargas para disfrutar offline'],
    },
    {
        title: 'Platino',
        price: '12x $24.900,00 /mes',
        description: 'Precio total anual $298.800,00',
        features: ['4 dispositivos a la vez', 'Resolución Full HD y 4K Ultra HD', 'Audio Dolby Atmos', '100 descargas para disfrutar offline'],
    }

]);

const gridClasses = computed(() => {
    switch (subscriptions.value.length) {
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

const selectSubscription = (subscription) => {
    selectedSubscription.value = subscription;
    showForm.value = true;
};

const submitForm = () => {
    form.post('/subscription/submit', {
        onFinish: () => {
            form.reset();
            showForm.value = false;
            selectedSubscription.value = null;
        }
    });
};

// Props Definition
const props = defineProps({
    color: {
        type: String,
        default: 'white'
    },
    microsite: {
        type: Array,
        default: null
    }
});

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
</script>

<template>
    <div :class="['bg-gradient-to-b min-h-screen flex flex-col items-center justify-center p-6', colorClasses.backgroundFrom, colorClasses.backgroundTo]">

       <div v-if="microsite.logo" class="flex lg:justify-center lg:col-start-2">
           <div class="group flex items-center rounded-xl mb-6">
               <img  class="rounded-xl w-12 object-contain mr-2" :src="`/storage/${microsite.logo}`" alt="Logo">
               <h1 :class="['m-1 group-hover:text-gray-500', colorClasses.primaryText]">{{microsite.name}}</h1>
           </div>
       </div>

        <h1 :class="['text-3xl font-bold', colorClasses.primaryText]">Elige tu plan</h1>
        <p class="text-center mb-6" :class="colorClasses.primaryText">Información adicional de la suscripción.</p>

        <div v-if="!showForm" class="grid gap-6 w-full max-w-7xl" :class="gridClasses">
            <div
                v-for="(subscription, index) in subscriptions"
                :key="index"
                @click="selectSubscription(subscription)"
                :class="[
                    'shadow-xl bg-gradient-to-r rounded-lg p-6 w-full text-center transition-transform duration-300 ease-in-out transform hover:scale-105 hover:shadow-2xl cursor-pointer',
                    colorClasses.cardFrom,
                    colorClasses.cardTo,
                    colorClasses.primaryText,
                ]"
            >
                <h2 :class="['lg:text-3xl md:text-3xl sm:text-4xl font-bold mb-2', colorClasses.primaryText]">{{ subscription.title }}</h2>
                <p :class="['lg:text-4xl md:text-3xl sm:text-4xl font-bold mb-4', colorClasses.secondaryText]">{{ subscription.price }}</p>
                <p class="mb-4 lg:text-2xl md:text-xl sm:text-3xl">{{ subscription.description }}</p>
                <div class="flex justify-center">
                    <ul class="grid grid-cols-1 gap-1">
                        <li
                            v-for="(feature, featureIndex) in subscription.features"
                            :key="featureIndex"
                            :class="['flex items-center lg:text-md md:text-sm sm:text-2xl', colorClasses.primaryText]"
                        >
                            <CheckBadgeIcon :class="['w-6 h-6 mr-2 flex-shrink-0 text-bold', colorClasses.secondaryText]" />
                            <span class="flex-shrink-0 rounded-2xl">{{ feature }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div v-else class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold mb-4">Formulario de Suscripción</h2>
            <p class="text-center mb-6">Por favor, ingresa la referencia y tu correo electrónico.</p>

            <form @submit.prevent="submitForm">
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Suscripción Seleccionada:</label>
                    <div class="bg-gray-100 p-2 rounded">{{ selectedSubscription?.title }}</div>
                </div>

                <div class="mb-4">
                    <label for="reference" class="block text-gray-700 font-bold mb-2">Referencia:</label>
                    <input
                        v-model="form.reference"
                        id="reference"
                        type="text"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Ingresa tu referencia"
                    />
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Correo Electrónico:</label>
                    <input
                        v-model="form.email"
                        id="email"
                        type="email"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Ingresa tu correo electrónico"
                    />
                </div>

                <div class="flex items-center justify-between">
                    <button
                        type="submit"
                        :class="[
                            'text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline border',
                            colorClasses.button
                        ]"
                    >
                        Enviar
                    </button>
                    <button
                        type="button"
                        @click="showForm = false"
                        :class="[
                            'font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline',
                            colorClasses.buttonText
                        ]"
                    >
                        Volver
                    </button>
                </div>
            </form>
        </div>

        <p :class="[ 'text-sm text-center mt-6 text-gray-50']" v-if="!showForm">*Información adicional de vencimientos.</p>
    </div>
</template>

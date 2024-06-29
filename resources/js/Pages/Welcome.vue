<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import { ArrowLongRightIcon, GlobeAltIcon, MagnifyingGlassIcon } from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    microsites: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');

const filteredMicrosites = computed(() => {
    return props.microsites.filter(microsite =>
        microsite.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
</script>

<template>
    <Head title="Microsites" />
    <div class="w-screen h-screen bg-gray-100">
        <header class="rounded-md w-full flex justify-between items-center p-6 bg-white">
            <div class="flex lg:justify-center lg:col-start-2 text-gray-700 dark:text-gray-800">
                <GlobeAltIcon class="w-6 hover:text-gray-500" />
                <h1 class="m-1 hover:text-gray-500">Microsites</h1>
            </div>
            <nav class="flex space-x-4">
                <Link
                    :href="route('login')"
                    class="bg-gray-100 rounded-md px-3 py-2 text-gray-800 dark:text-gray-800 transition hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus-visible:ring-[#FF2D20]"
                >
                    Iniciar Sesión
                </Link>
                <Link
                    :href="route('register')"
                    class="bg-gray-100 rounded-md px-3 py-2 text-gray-800 dark:text-gray-800 transition hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus-visible:ring-[#FF2D20]"
                >
                    Registro
                </Link>
            </nav>
        </header>
        <div class="max-w-sm mx-auto relative my-6">
            <input type="text"
                   v-model="searchQuery"
                   class="py-3 px-10 block w-full border-gray-200 rounded-full text-sm"
                   placeholder="Buscar micrositio">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-500" />
        </div>

        <main class="mx-4 md:mx-10 lg:mx-20 xl:mx-40 my-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <div v-for="microsite in filteredMicrosites" :key="microsite.id" class="bg-white shadow-md rounded-xl">
                    <div class="p-6">
                        <div class="m-1" v-if="microsite.logo">
                            <img class="w-full h-16 object-contain" :src="`/storage/${microsite.logo}`" alt="Logo">
                        </div>
                        <h5 class="text-xl font-semibold text-blue-gray-900">{{ microsite.name }}</h5>
                        <p class="text-base font-light text-inherit">{{ microsite.microsite_type }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ microsite.category_name }}</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a
                            class="flex items-center justify-center px-5 py-2 text-xs font-bold text-center text-gray-900 uppercase rounded-lg transition-all select-none hover:bg-gray-900/10 active:bg-gray-900/20"
                            :href="route('register')"
                        >
                            Ingresar
                            <ArrowLongRightIcon class="px-1 w-6 hover:text-gray-500" />
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

</template>


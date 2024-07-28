<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import { ArrowLongRightIcon, MagnifyingGlassIcon, PhotoIcon } from "@heroicons/vue/24/outline/index.js";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";

const props = defineProps({
    microsites: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = 8;

const filteredMicrosites = computed(() => {
    return props.microsites.filter(microsite =>
        microsite.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const paginatedMicrosites = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredMicrosites.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredMicrosites.value.length / itemsPerPage) || 1;
});

const changePage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const goToPreviousPage = () => {
    if (currentPage.value > 1) {
        changePage(currentPage.value - 1);
    }
};

const goToNextPage = () => {
    if (currentPage.value < totalPages.value) {
        changePage(currentPage.value + 1);
    }
};

const isPreviousPageDisabled = computed(() => currentPage.value === 1);
const isNextPageDisabled = computed(() => currentPage.value === totalPages.value);

</script>

<template>
    <Head :title=" $t('welcome')" />
    <div class="flex flex-col min-h-screen bg-white">
        <header class="rounded-md w-full flex justify-between items-center p-10 bg-white">
            <ApplicationLogo/>
            <nav class="flex space-x-4">
                <LanguageDropdown />

                <Link
                    :href="route('login')"
                    class="bg-gray-500 text-white rounded-full px-4 py-2 text-sm font-semibold transition transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                    {{$t('login')}}
                </Link>
                <Link
                    :href="route('register')"
                    class="bg-gray-500 text-white rounded-full px-4 py-2 text-sm font-semibold transition transform hover:scale-105 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
                >
                    {{$t('register')}}
                </Link>
            </nav>
        </header>

        <div class="text-center mt-6 px-2 max-w-xl mx-auto my-3">
            <h2 class="text-2xl font-bold text-gray-800 leading-snug">
                {{ $t('titleWelcome') }}
            </h2>
        </div>

        <div class="max-w-sm mx-auto relative my-5">
            <input type="text"
                   v-model="searchQuery"
                   class="py-2 px-11 block w-full border-gray-400 rounded-full text-sm"
                   :placeholder="$t('searchMicrosite')">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-500" />
        </div>

        <main class="md:mx-10 lg:mx-20 xl:mx-40">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div v-for="microsite in paginatedMicrosites" :key="microsite.id"
                     class="bg-gray-100 shadow rounded-xl transition-transform transform hover:-translate-y-1 hover:shadow-xl hover:bg-gray-200">
                    <div class="p-5">
                        <div class="m-1 rounded-lg overflow-hidden flex justify-center items-center ">
                            <img v-if="microsite.logo" class="rounded-lg h-16 object-contain" :src="`/storage/${microsite.logo}`" alt="Logo">
                            <PhotoIcon v-else class="w-full h-16 text-gray-400 object-contain hover:text-gray-600"/>
                        </div>
                        <h5 class="text-xl font-semibold text-blue-gray-900">{{ microsite.name }}</h5>
                        <p class="text-base font-light text-inherit">{{ $t(`micrositeTypes.${microsite.microsite_type}`) }}</p>
                        <p class="mt-1 text-sm text-gray-600">{{ microsite.category_name }}</p>
                    </div>
                    <div class="p-6 pt-0">
                        <a
                            class="flex items-center justify-center px-5 py-2 text-xs font-bold text-center text-gray-900 uppercase rounded-lg transition-all select-none hover:bg-gray-900/10 active:bg-gray-900/20"
                            :href="route('payment')"
                        >
                            {{ $t('enter') }}
                            <ArrowLongRightIcon class="px-1 w-6 hover:text-gray-500" />
                        </a>
                    </div>
                </div>
            </div>


            <div class="flex items-center justify-center mt-6 space-x-2">
                <button
                    @click="goToPreviousPage"
                    :disabled="isPreviousPageDisabled"
                    class="flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-full shadow-md hover:bg-gray-700 disabled:bg-gray-300 transition-colors duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    <span class="sr-only">Anterior</span>
                </button>

                <span class="px-4 py-2 text-gray-800 bg-gray-100 border border-gray-300 rounded-full">
                    {{ $t('page') }} {{ currentPage }} {{ $t('of') }} {{ totalPages }}
                </span>

                <button
                    @click="goToNextPage"
                    :disabled="isNextPageDisabled"
                    class="flex items-center justify-center px-4 py-2 bg-gray-600 text-white rounded-full shadow-md hover:bg-gray-700 disabled:bg-gray-300 transition-colors duration-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="sr-only">Siguiente</span>
                </button>
            </div>

        </main>
    </div>
    <FooterIndex/>

</template>

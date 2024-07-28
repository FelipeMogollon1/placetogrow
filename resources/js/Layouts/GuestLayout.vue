<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import { ArrowSmallLeftIcon } from "@heroicons/vue/24/outline/index.js";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <header class="bg-white shadow-md rounded-md w-full flex justify-between items-center p-6">
            <Link
                :href="route('Welcome')"
                class="flex items-center text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <ArrowSmallLeftIcon class="w-6 h-6 mr-2 text-gray-600 hover:text-gray-500"/>
                {{$t('return')}}
            </Link>
            <ApplicationLogo/>
            <nav class="flex items-center space-x-4">
                <LanguageDropdown />
            </nav>
        </header>
        <main class="flex-1 flex flex-col items-center justify-center bg-white p-6">
            <div class="w-full sm:max-w-md bg-white shadow-lg rounded-lg overflow-hidden p-4">
                <slot />
            </div>
        </main>
        <FooterIndex />
    </div>
</template>

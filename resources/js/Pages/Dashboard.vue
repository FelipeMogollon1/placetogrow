<template>
    <AuthenticatedLayout>
        <Head :title="$t('dashboard')" />
        <div class="bg-white shadow-lg pt-5 pb-2">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="flex flex-col items-center max-w-2xl mx-auto lg:text-center">
                    <p class="mt-2 text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold tracking-tight text-orange-400 ">
                        {{ $t('welcome_title') }}
                    </p>
                    <span class="mt-2 text-lg sm:text-xl md:text-2xl lg:text-3xl font-semibold tracking-tight text-gray-600 capitalize">
                         {{ props.auth.user.name }}
                    </span>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import {ref} from "vue";

const page = usePage();
const props = ref(page.props);
</script>

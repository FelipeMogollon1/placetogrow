<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";

const page = usePage()
const microsite = ref(page.props.microsite);

</script>

<template>

    <Head title="Detalle del Micrositio"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle del Micrositio</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Lista del Micrositio
                </Link>
            </div>
        </template>
        <div class="container mx-auto p-10 my-5 bg-white rounded-2xl shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">slug:</span>
                    <span class="font-bold">{{ microsite.slug }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Nombre:</span>
                    <span class="font-bold">{{ microsite.name }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Administrador:</span>
                    <span class="font-bold">{{ microsite.user.name }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Tipo:</span>
                    <span class="font-bold">{{ microsite.microsite_type }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Tipo de documento:</span>
                    <span class="font-bold">{{ microsite.document_type }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Documento:</span>
                    <span class="font-bold">{{ microsite.document }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Moneda:</span>
                    <span class="font-bold">{{ microsite.currency }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Tiempo de expiración en segundos:</span>
                    <span class="font-bold">{{ microsite.payment_expiration_time }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Categoría:</span>
                    <span class="font-bold">{{ microsite.category.name }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Descripción de la categoría:</span>
                    <span class="font-bold">{{ microsite.category.description }}</span>
                </div>

                <div class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Fecha de habilitación:</span>
                    <span class="font-bold">{{ microsite.enabled_at }}</span>
                </div>

                <div v-if="microsite.logo" class="flex flex-col p-4 bg-gray-50 rounded-lg shadow">
                    <span class="text-gray-500 font-semibold">Logo:</span>
                    <img class="w-20 h-20 mt-2 rounded-full shadow" :src="`/storage/${microsite.logo}`" alt="logo">
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

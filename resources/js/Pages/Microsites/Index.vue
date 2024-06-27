<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {defineProps} from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import SecondaryTable from "@/Layouts/Organisms/SecondaryTable.vue";

defineProps({
    microsites: {
        type: Array,
        default: () => []
    }
});

const headers = ["Nombre","Tipo de Documento","Documento","Moneda","Tiempo de expiración","Tipo de Micrositio", "Tipo de Categoria"];


</script>

<template>
    <Head title="Microsites" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Micrositios</h2>
                <Link
                    :href="route('microsites.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Crear un nuevo micrositio
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-center py-4">
                        <SecondaryTable :data="microsites" :headers="headers" />
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>


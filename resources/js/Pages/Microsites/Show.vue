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

        <div class="grid grid-cols-3 m-10 bg-white rounded-2xl ">

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Slug:</span>
                <span class="font-bold">{{microsite.slug}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Nombre:</span>
                <span class="font-bold">{{microsite.name}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Tipo:</span>
                <span class="font-bold">{{microsite.microsite_type}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Tipo de documento:</span>
                <span class="font-bold">{{microsite.document_type}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Documento:</span>
                <span class="font-bold">{{microsite.document}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Moneda:</span>
                <span class="font-bold">{{microsite.currency}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Tiempo de expiración en segundos:</span>
                <span class="font-bold">{{microsite.payment_expiration_time}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Categoria:</span>
                <span class="font-bold">{{microsite.category.name}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Descripción de la categoria:</span>
                <span class="font-bold">{{microsite.category.description}}</span>
            </div>

            <div class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Fecha habilitación:</span>
                <span class="font-bold">{{microsite.enabled_at}}</span>
            </div>

            <div v-if="microsite.logo" class="grid grid-rows-2 m-10">
                <span class="text-gray-400 font-bold">Logo:</span>
                <img class="w-10 h-10" :src="`/storage/${microsite.logo}`" alt="logo">
            </div>

            <div class="grid grid-rows-2 m-10">

            </div>

        </div>
    </AuthenticatedLayout>
</template>

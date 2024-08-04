<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { Link } from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ArrowSmallLeftIcon} from "@heroicons/vue/24/outline/index.js";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";

function printPage() {
    window.print();
}
</script>

<template>
    <header class="bg-white shadow-md rounded-md w-full flex justify-between items-center p-6">
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

    <main class="flex-grow flex items-center justify-center bg-white my-6">
        <div id="form" class="bg-white p-4 m-4 rounded-3xl border border-gray-200 w-full max-w-4xl">
            <application-logo />
            <div class="m-3 text-3xl">
                <span class="block font-extrabold">Comprobante</span>
                <span class="block"> de pago en línea</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4 m-5 ">
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Pago realizado por:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Nro. de factura:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Descripción del pago:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Nro. de referencia:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Fecha y hora de la transacción:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Nro. de comprobante:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Valor pagado:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">Producto:</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">Nombre</dd>
                    </div>
                </div>

                <div class="col-span-1 flex justify-center sm:col-span-2 space-x-4">
                    <Link
                        :href="route('Welcome')"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                    >
                        {{$t('return')}}
                    </Link>
                    <button
                        @click="printPage"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-500 hover:bg-gray-600 border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                    >
                        Imprimir Comprobante
                    </button>
                </div>
            </div>

    </main>

    <FooterIndex />
</template>

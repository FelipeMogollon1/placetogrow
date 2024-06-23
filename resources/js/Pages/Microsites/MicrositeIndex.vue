<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { routes } from "/resources/js/routes.js";
import Table from "@/Layouts/Organisms/Table.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const microsites = ref([]);
const headers = ["Id","Nombre","Tipo de Documento","Docuemento","Creación","Moneda","Tiempo de Expiración en segundos","Tipo de Micrositio",
    "Tipo de Categoria"];

const getMicrosites = async () => {
    try {
        const micrositeIndexUrl = routes.admin.microsites.index;
        const response = await axios.get(micrositeIndexUrl);
        microsites.value = response.data;
       // console.log(JSON.stringify(microsites.value, null, 2));
    } catch (error) {
        console.error('Error fetching microsites:', error);
    }
};

getMicrosites();

</script>

<template>
    <Head title="Microsites" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Microsite</h2>
        </template>
        <div class="py-4">
            <Table :data="microsites" :headers="headers" />
        </div>
    </AuthenticatedLayout>
</template>

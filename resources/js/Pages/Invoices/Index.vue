<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PaymentTable from "@/Layouts/Organisms/PaymentTable.vue";
import {MagnifyingGlassIcon} from "@heroicons/vue/24/outline/index.js";
import { ref, computed } from "vue";
import { route } from "ziggy-js";
import InvoiceTable from "@/Layouts/Organisms/InvoiceTable.vue";

defineProps({
    invoices: {
        type: Object,
        default: () => []
    }
});

const headers = [
    "reference",
    "microsite_type",
    "name",
    "surname",
    "email",
    "document_type",
    "document",
    "currency_type",
    "amount",
    "status",

];


const form = useForm({
    invoices: null
});

const fileInput = ref(null);


const handleButtonClick = () => {
    fileInput.value?.click();
};


const handleFileChange = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        form.invoices = file;
    }
};


const fileName = computed(() => form.invoices ? form.invoices.name : 'No ha seleccionado un archivo');


const submitForm = () => {
    const formData = new FormData();
    formData.append('invoices', form.invoices);

    form.post(route('invoices.import'), {
        data: formData,
        onSuccess: () => {
            console.log('Importación exitosa');
        },
        onError: (error) => {
            console.log('Error en la importación', error);
        },
        forceFormData: true
    });
};
</script>

<template>
    <Head :title="$t('microsites')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Importar Facturas</h2>
            </div>
        </template>

        <form @submit.prevent="submitForm" class="space-y-6 p-6 mt-5 bg-white rounded-lg shadow-md">

            <div>
                <p class="text-gray-700 mb-2">Selecciona un archivo para importar las facturas en formato CSV, XLSX o XLS.</p>
               <!-- <p>
                    <a :href="route('invoices.index')" class="text-blue-600 hover:underline">
                        Descargar plantilla de ejemplo
                    </a>
                </p>-->
            </div>

            <div class="flex justify-between">
                <div class="flex items-center space-x-4">
                    <label class="flex items-center cursor-pointer">
                            <span
                                class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Elegir archivo
                            </span>
                        <input
                            ref="fileInput"
                            type="file"
                            accept=".csv, .xlsx, .xls"
                            class="hidden"
                            @change="handleFileChange"
                            required
                        />
                    </label>
                    <span class="text-gray-600">{{ fileName }}</span>
                    <p class="text-red-500" v-if="form.errors.invoices">{{ form.errors.invoices }}</p>
                </div>


                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Importar
                </button>
            </div>
        </form>

        <div class="max-w-sm mx-auto relative mt-6">
            <input type="text"
                   v-model="search"
                   class="py-2 px-11 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-full text-sm"
                   placeholder="Buscar factura...">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-600" />
        </div>

        <InvoiceTable :data="invoices.data" :paginator="invoices" :headers="headers" />
    </AuthenticatedLayout>
</template>

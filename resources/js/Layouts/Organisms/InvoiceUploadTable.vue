<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { EyeIcon, CreditCardIcon } from '@heroicons/vue/24/outline';
import Paginator from '@/Components/Paginator.vue';
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";
import {TrashIcon, XCircleIcon} from "@heroicons/vue/24/outline/index.js";


const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    headers: {
        type: Array,
        required: true
    },
    paginator: {
        type: Object,
        required: true
    }
});

const sortKey = ref('');
const sortOrder = ref('asc');

const sortData = (key) => {
    sortOrder.value = (sortKey.value === key && sortOrder.value === 'asc') ? 'desc' : 'asc';
    sortKey.value = key;

    props.data.sort((a, b) => {
        if (a[key] < b[key]) return sortOrder.value === 'asc' ? -1 : 1;
        if (a[key] > b[key]) return sortOrder.value === 'asc' ? 1 : -1;
        return 0;
    });
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
}

</script>

<template>
    <div class="flex flex-col mt-8">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-2xl border border-1 shadow">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th
                                v-for="header in headers"
                                :key="header"
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                @click="sortData(header)"
                                :class="{ 'cursor-pointer': true, 'bg-gray-300': sortKey === header }"
                            >
                               {{ $t(`invoices.${header}`) }}
                                <span v-if="sortKey === header">
                                    {{ sortOrder === 'asc' ? '▲' : '▼' }}
                                </span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t('microsites_table.actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                        <tr v-if="!props.data.length" class="text-center">
                            <td colspan="100%" class="px-6 py-3 text-sm text-gray-500">
                                {{ $t('no_information') }}
                            </td>
                        </tr>

                        <tr
                            v-for="(item, index) in props.data"
                            :key="index"
                            class="transition duration-300 ease-in-out hover:bg-gray-100"
                        >
                            <td
                                v-for="header in headers"
                                :key="header"
                                class="px-6 py-3 whitespace text-sm text-gray-900"
                            >

                                <template v-if="header === 'created_at'">
                                    {{ formatDate(item[header]) }}
                                </template>

                                <template v-else-if="header === 'storage_path'">
                                    <a :href="`/storage/${item[header]}`" class="text-blue-600 hover:underline" download>
                                        Descargar Original
                                    </a>
                                </template>

                                <template v-else-if="header === 'error_file_path'">
                                    <a :href="`/storage/${item[header]}`" class="text-blue-600 hover:underline" download>
                                        Descargar errores
                                    </a>
                                </template>
                                <span v-else>
                                    {{ item[header] }}
                                </span>

                            </td>
                            <td>
                                <!--
                                <div class="text-gray-400 flex justify-center">

                                    <Link v-if="can('invoices.processPayment') && item.status !== 'APPROVED'"
                                          class="mx-1"
                                          :href="route('invoices.processPayment', item.id)"
                                          method="POST"
                                    >
                                        <CreditCardIcon title="Realizar el pago" class="w-6 hover:text-gray-500"/>
                                    </Link>

                                    <Link v-if="can('invoices.show')" class="mx-1" :href="route('invoices.show', item.id)">
                                        <EyeIcon class="w-6 hover:text-gray-500"/>
                                    </Link>

                                    <Link v-if="can('invoices.destroy') && item.status !== 'APPROVED'"
                                          :href="route('invoices.destroy', item.id)"
                                          method="delete" as="button"
                                    >
                                        <TrashIcon class="w-6 hover:text-red-500"/>
                                    </Link>

                                </div>
                                -->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end items-center mt-2">
        <p class="text-sm text-gray-600">{{ $t('total_records') }}: {{ paginator.total }}</p>
    </div>
    <div class="flex justify-center">
        <Paginator :paginator="paginator.links"/>
    </div>
</template>


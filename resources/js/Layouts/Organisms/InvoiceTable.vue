<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { EyeIcon, CreditCardIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Paginator from '@/Components/Paginator.vue';
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";
import Modal from "@/Components/Modal.vue";
import {Inertia} from "@inertiajs/inertia";

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

const statusColors = {
    PENDING: 'yellow',
    APPROVED: 'green',
    REJECTED: 'red',
    APPROVED_PARTIAL: 'cyan',
    PARTIAL_EXPIRED: 'orange',
    UNKNOWN: 'blue'
};

const typeMicrosite = {
    donation: 'violet',
    invoice: 'orange',
    subscription: 'cyan'
};

const isModalOpen = ref(false);
const selectedItem = ref(null);
const today = new Date();

const isLatePayment = (paymentDate) => {
    const dueDate = new Date(paymentDate);
    return dueDate < today;
};

const openModal = (item) => {
    if (isLatePayment(item.expiration_date) && item.additionalValueType !== null || item.additionalValue !== null) {
        selectedItem.value = item;
        isModalOpen.value = true;
    } else {
        processPayment(item);
    }
};
const processPayment = (item) => {
    Inertia.post(route('invoices.processPayment', item.id));
};
const confirmPayment = () => {
    if (selectedItem.value) {
        processPayment(selectedItem.value);
        isModalOpen.value = false;
        selectedItem.value = null;
    }
};
const cancelPayment = () => {
    isModalOpen.value = false;
    selectedItem.value = null;
};
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
                                <template v-if="header === 'microsite_type'">
                                    <span-form class="capitalize" :color="typeMicrosite[item[header]]">
                                        {{ $t(`micrositeTypes.${item[header]}`) }}
                                    </span-form>
                                </template>

                                <template v-else-if="header === 'amount'">
                                    <span v-if="item['currency'] === 'USD'">${{ item[header].toLocaleString('en-US') }}</span>
                                    <span v-else>${{ item[header].toLocaleString('es-CO') }}</span>
                                </template>

                                <template v-else-if="header === 'status'">
                                    <span-form class="capitalize" :color="statusColors[item[header]]">
                                        {{ $t(`payment_status.${item[header]}`) }}
                                    </span-form>
                                </template>

                                <span v-else>
                                    {{ item[header] }}
                                </span>
                            </td>
                            <td>
                                <div class="text-gray-400 flex justify-center">
                                    <button v-if="can('invoices.processPayment') && item.status !== 'APPROVED'"
                                            class="mx-1"
                                            :title="$t('process_payment')"
                                            @click="openModal(item)"
                                    >
                                        <CreditCardIcon class="w-6 hover:text-gray-500"/>
                                    </button>

                                    <Link v-if="can('invoices.show')" class="mx-1"
                                          :href="route('invoices.show', item.id)"
                                          :title="$t('show_invoice')"
                                    >
                                        <EyeIcon class="w-6 hover:text-gray-500"/>
                                    </Link>

                                    <Link v-if="can('invoices.destroy') && item.status !== 'APPROVED'"
                                          :href="route('invoices.destroy', item.id)"
                                          method="delete" as="button"
                                          :title="$t('delete_invoice')"
                                    >
                                        <TrashIcon class="w-6 hover:text-red-500"/>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <Modal v-model:show="isModalOpen" maxWidth="2xl">
        <template v-slot:default>
            <div class="flex flex-col items-center justify-center my-10 mx-1">
                <h1 class="text-2xl font-semibold mb-4 text-center">
                    {{ $t('invoices.late_payment') }}
                </h1>

                <div v-if="selectedItem.additionalValueType === 'FIXED'" class="bg-white p-5 shadow rounded w-full max-w-xl">
                    <p class="text-gray-700">
                        {{ $t('invoices.invoice_expired_on') }}
                        <span class="font-semibold my-1"> {{ selectedItem.expiration_date }}</span>
                        {{ $t('invoices.expired') }}.
                    </p>

                    <p class="text-gray-700 mt-5">{{ $t('invoices.convenience') }}:</p>

                    <p class="text-gray-700 mt-5">
                        {{ $t('invoices.fixed_increment') }}:
                        <span class="font-semibold">$ {{ selectedItem.additionalValue.toLocaleString() }}</span>
                    </p>
                    <p class="text-gray-700">
                        {{ $t('invoices.total_with_increment') }}:
                        <span class="font-semibold">$ {{ (parseFloat(selectedItem.amount) + parseFloat(selectedItem.additionalValue)).toLocaleString() }}</span>
                    </p>

                </div>

                <div v-else-if="selectedItem.additionalValueType === 'PERCENTAGE'" class="bg-white p-5 shadow rounded w-full max-w-xl">
                    <p class="text-gray-700">
                        {{ $t('invoices.invoice_expired_on') }} <span class="font-semibold">{{ selectedItem.expiration_date }}</span>
                        {{ $t('invoices.expired') }}.
                    </p>
                    <p class="text-gray-700 mt-5">{{ $t('invoices.convenience') }}:</p>
                    <p class="text-gray-700 mt-5">
                        {{ $t('invoices.percentage_increment') }}:
                        <span class="font-semibold">{{ selectedItem.additionalValue }}%</span>
                    </p>
                    <p class="text-gray-700">
                        {{ $t('invoices.total_with_increment') }}:
                        <span class="font-semibold">
                        $ {{ (parseFloat(selectedItem.amount) + (parseFloat(selectedItem.amount) * parseFloat(selectedItem.additionalValue) / 100)).toLocaleString() }}
                    </span>
                    </p>
                </div>

                <div class="flex justify-center mt-4 space-x-4">
                    <button @click="cancelPayment" class="inline-flex items-center px-4 py-2 bg-transparent border border-gray-500 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-500 hover:text-white focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ $t('invoices.cancel') }}
                    </button>
                    <button @click="confirmPayment" class="inline-flex items-center px-10 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ $t('invoices.continue_payment') }}
                    </button>
                </div>
            </div>
        </template>
    </Modal>


    <div class="flex justify-end items-center mt-2">
        <p class="text-sm text-gray-600">{{ $t('total_records') }}: {{ paginator.total }}</p>
    </div>
    <div class="flex justify-center">
        <Paginator :paginator="paginator.links"/>
    </div>
</template>

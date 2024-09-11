<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Paginator from '@/Components/Paginator.vue';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline/index.js';
import Modal from '@/Components/Modal.vue';
import Edit from '@/Pages/SubscriptionPlans/Edit.vue';
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";

const props = defineProps({
    data: {
        type: Array,
        required: true,
    },
    headers: {
        type: Array,
        required: true,
    },
    paginator: {
        type: Object,
        required: true,
    },
    microsite: {
        type: Object,
        required: true,
    },
    periods: {
        type: Object,
        required: true,
    },
    currency:{
        type: Object,
        required: true,
    }
});

const sortKey = ref('');
const sortOrder = ref('asc');
const selectedSubscription = ref(null);
const isModalOpenEdit = ref(false);
const emit = defineEmits(['closeModal']);

const sortData = (key) => {
    sortOrder.value = sortKey.value === key && sortOrder.value === 'asc' ? 'desc' : 'asc';
    sortKey.value = key;

    props.data.sort((a, b) => {
        if (a[key] < b[key]) return sortOrder.value === 'asc' ? -1 : 1;
        if (a[key] > b[key]) return sortOrder.value === 'asc' ? 1 : -1;
        return 0;
    });
};

const openModalEdit = (item) => {
    selectedSubscription.value = {
        ...item,
        description: JSON.parse(item.description),
    };
    isModalOpenEdit.value = true;
};

const handleCloseModal = () => {
    isModalOpenEdit.value = false;
};


const typeMicrosite = {
    daily : 'violet',
    weekly: 'orange',
    monthly: 'cyan',
    yearly: 'blue',
};
</script>

<template>
    <Modal v-model:show="isModalOpenEdit">
        <template v-slot:default>
            <h1 class="text-2xl font-semibold m-4">{{ $t('subscription.newSubscriptionPlan') }}</h1>
            <Edit :periods="periods"
                  :microsite="microsite"
                  :subscription="selectedSubscription"
                  :currency="currency"
                  @closeModal="handleCloseModal"
            />
        </template>
    </Modal>
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
                                {{ $t(`subscription.${header}`) }}
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

                                <template v-if="header === 'subscription_period'">
                                    <span-form class="capitalize" :color="typeMicrosite[item[header]]">
                                        {{ $t(`subscription.${item[header]}`) }}
                                    </span-form>
                                </template>

                                <template v-else-if="header === 'amount'">
                                    <span v-if="item['currency'] === 'USD'">${{ item[header].toLocaleString('en-US') }}</span>
                                    <span v-else>${{ item[header].toLocaleString('es-CO') }}</span>
                                </template>

                                <span v-else>
                                    {{ item[header] }}
                                </span>

                            </td>
                            <td>
                                <div class="text-gray-400 flex justify-center">
                                    <button @click="openModalEdit(item)"
                                            class="inline-flex items-center px-4 py-2  rounded-md font-semibold text-xs text-gray-400 uppercase tracking-widest  focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                           <span class="material-symbols-outlined">
                                                <PencilIcon class="w-6 hover:text-gray-500"/>
                                            </span>
                                    </button>
                                    <Link
                                        class="m-1"
                                        :href="route('subscriptionsPlan.destroy',item.id)"
                                        method="delete"
                                    >
                                       <span class="material-symbols-outlined">
                                            <TrashIcon class="w-6 hover:text-red-500" />
                                        </span>
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
    <div class="flex justify-center mt-4">
        <Paginator :paginator="paginator.links"/>
    </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from "vue";
import { Head, usePage } from '@inertiajs/vue3';
import Chart from "chart.js/auto";
import { Inertia } from "@inertiajs/inertia";
import { useI18n } from 'vue-i18n';
import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from "@headlessui/vue";
import { CheckIcon, ChevronUpDownIcon, PhotoIcon } from "@heroicons/vue/24/outline";

const { t } = useI18n();
const page = usePage();
const props = ref(page.props);

const microsites = ref(props.value.microsites);
const selectedMicrosite = ref(props.value.selectedMicrosite);
const startDate = ref(props.value.startDate);
const endDate = ref(props.value.endDate);
const errorMessage = ref('');

const metrics = ref({
    totalPaid: props.value.totalPaid,
    totalPending: props.value.totalPending,
    totalOverdue: props.value.totalOverdue,
});

const salesChart = ref(null);
const paidPendingChart = ref(null);
const pendingOverdueChart = ref(null);

const filterInvoices = async () => {
    if (!startDate.value || !endDate.value) {
        errorMessage.value = t("error.select_dates");
        return;
    }
    if (new Date(startDate.value) > new Date(endDate.value)) {
        errorMessage.value = t("error.start_date_before_end");
        return;
    }

    errorMessage.value = '';
    await Inertia.get('/dashboard', {
        start_date: startDate.value,
        end_date: endDate.value,
        microsite_id: selectedMicrosite.value ? selectedMicrosite.value : '*',
    });
};

onMounted(() => {
    if (props.value.startDate) {
        const date = new Date(props.value.startDate);
        startDate.value = date.toISOString().split('T')[0]; // Solo la parte de la fecha
    }
    if (props.value.endDate) {
        const date = new Date(props.value.endDate);
        endDate.value = date.toISOString().split('T')[0]; // Solo la parte de la fecha
    }
    const ctxSales = salesChart.value.getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: [t('invoices.paid'), t('invoices.pending'), t('invoices.overdue')],
            datasets: [{
                label: t('invoices.label'),
                data: [metrics.value.totalPaid, metrics.value.totalPending, metrics.value.totalOverdue],
                backgroundColor: [
                    'rgba(104,216,104,0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(104,216,104, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1,
                barPercentage: 0.5,
                categoryPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuad'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: t('invoices.quantity')
                    }
                }
            }
        }
    });

    const ctxPaidPending = paidPendingChart.value.getContext('2d');
    new Chart(ctxPaidPending, {
        type: 'doughnut',
        data: {
            labels: [t('invoices.paid'), t('invoices.pending')],
            datasets: [{
                label: t('invoices.label'),
                data: [metrics.value.totalPaid, metrics.value.totalPending],
                backgroundColor: [
                    'rgba(104,216,104, 0.3)',
                    'rgba(255, 206, 86, 0.3)',
                ],
                borderColor: [
                    'rgba(104,216,104, 1)',
                    'rgba(255, 206, 86, 1)',
                ],
                borderWidth: 2,
                hoverOffset: 10,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        padding: 20,
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                },
            },
            cutout: '60%',
        }
    });

    const ctxPendingOverdue = pendingOverdueChart.value.getContext('2d');
    new Chart(ctxPendingOverdue, {
        type: 'pie',
        data: {
            labels: [t('invoices.pending'), t('invoices.overdue')],
            datasets: [{
                label: t('invoices.label'),
                data: [metrics.value.totalPending, metrics.value.totalOverdue],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.3)',
                    'rgba(255, 99, 132, 0.3)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2,
                hoverOffset: 10,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        padding: 20,
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                },
            },
        }
    });
});
</script>

<template>
    <Head :title="$t('dashboard')" />
    <AuthenticatedLayout>

        <div class="pb-5">
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

        <div v-if="can('dashboard.index')" class="p-6 bg-white shadow-xl rounded-3xl">
            <form @submit.prevent="filterInvoices" class="flex flex-col items-center justify-center mb-4 w-full">

                <div class="grid grid-cols-1 md:grid-cols-4 gap-2 w-full md:max-w-2xl">
                    <div class="flex flex-col">
                        <label for="startDate" class="text-gray-700 mb-1">{{ $t('invoices.start_date') }}</label>
                        <input
                            id="startDate"
                            type="date"
                            v-model="startDate"
                            class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                        />
                    </div>

                    <div class="flex flex-col">
                        <label for="endDate" class="text-gray-700 mb-1">{{ $t('invoices.end_date') }}</label>
                        <input
                            id="endDate"
                            type="date"
                            v-model="endDate"
                            class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                        />
                    </div>

                    <div>
                        <Listbox as="div" v-model="selectedMicrosite">
                            <ListboxLabel class="block text-sm font-medium leading-6 text-gray-900">
                                {{ $t('invoices.selectMicrosite') }}
                            </ListboxLabel>
                            <div class="relative mt-2">
                                <ListboxButton class="relative w-full cursor-default rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 sm:text-sm sm:leading-6">
                                    <div v-if="selectedMicrosite && selectedMicrosite !== '*'">
                                        <span class="flex items-center">
                                            <img v-if="selectedMicrosite.logo" class="h-6 w-6 flex-shrink-0 rounded-full" :src="`/storage/${selectedMicrosite.logo}`" alt="Logo">
                                            <PhotoIcon v-else class="h-6 w-6 flex-shrink-0 rounded-full"/>
                                            <span class="ml-3 block truncate">{{ selectedMicrosite.name }}</span>
                                        </span>
                                        <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                                        </span>
                                    </div>
                                    <div v-else-if="selectedMicrosite === '*'">
                                        <span class="ml-3 block truncate">{{ $t('invoices.allMicrosites') }}</span>
                                    </div>
                                    <div v-else>
                                        <span class="ml-3 block truncate">{{ $t('invoices.selectMicrosite') }}</span>
                                    </div>
                                </ListboxButton>

                                <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                                    <ListboxOptions class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                        <ListboxOption as="template" :value="null">
                                            <li class="relative cursor-default select-none py-2 pl-3 pr-9" :class="{'bg-orange-600 text-white': selectedMicrosite == null}">
                                                <div class="flex items-center">
                                                    <span class="ml-3 block truncate">{{ $t('invoices.allMicrosites') }}</span>
                                                </div>
                                            </li>
                                        </ListboxOption>

                                        <ListboxOption
                                            v-for="microsite in microsites"
                                            :key="microsite.id"
                                            :value="microsite"
                                            as="template"
                                        >
                                            <li class="relative cursor-default select-none py-2 pl-3 pr-9" :class="{'bg-orange-600 text-white': selectedMicrosite === microsite}">
                                                <div class="flex items-center">
                                                    <img v-if="microsite.logo" class="h-6 w-6 flex-shrink-0 rounded-full" :src="`/storage/${microsite.logo}`" alt="">
                                                    <PhotoIcon v-else class="h-6 w-6 flex-shrink-0 rounded-full" />
                                                    <span class="ml-3 block truncate">{{ microsite.name }}</span>
                                                </div>

                                                <span v-if="selectedMicrosite === microsite" class="absolute inset-y-0 right-0 flex items-center pr-4 text-orange-600">
                                                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                                </span>
                                            </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
                    </div>

                    <div class="flex items-center justify-center mt-5">
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-semibold text-white bg-orange-500 rounded hover:bg-orange-600 transition"
                        >
                            {{ $t('filter') }}
                        </button>
                    </div>
                </div>
                <div v-if="errorMessage" class="mt-2 text-red-600">{{ errorMessage }}</div>
            </form>

            <div class="flex flex-col items-center justify-center ">
                <div class="bg-gray-50 p-4 border border-1 rounded-lg w-full max-w-3xl shadow-lg">
                    <h2 class="font-semibold mb-2">{{ $t('invoice_summary') }}</h2>
                    <canvas ref="salesChart"></canvas>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="bg-gray-50 ml-10 p-3 border border-1 rounded-lg w-full max-w-md shadow-lg">
                    <h2 class="font-semibold mb-2">{{ $t('paid_vs_pending') }}</h2>
                    <canvas ref="paidPendingChart"></canvas>
                </div>
                <div class="bg-gray-50 ml-10 p-3 border border-1 rounded-lg w-full max-w-md shadow-lg">
                    <h2 class="font-semibold mb-2">{{ $t('pending_vs_overdue') }}</h2>
                    <canvas ref="pendingOverdueChart"></canvas>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-lg mt-6  border border-1 shadow-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 ">
                    <div class="bg-green-100 border-l-4 border-green-400 p-4 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="text-2xl font-semibold text-gray-700">{{ metrics.totalPaid }}</div>
                        <div class="text-sm font-medium text-green-600">{{ $t('invoices_paid') }}</div>
                    </div>

                    <div class="bg-yellow-100 border-l-4 border-yellow-400 p-4 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="text-2xl font-semibold text-gray-700">{{ metrics.totalPending }}</div>
                        <div class="text-sm font-medium text-yellow-600">{{ $t('invoices_pending') }}</div>
                    </div>

                    <div class="bg-red-100 border-l-4 border-red-400 p-4 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="text-2xl font-semibold text-gray-700">{{ metrics.totalOverdue }}</div>
                        <div class="text-sm font-medium text-red-600">{{ $t('invoices_overdue') }}</div>
                    </div>
                </div>
            </div>


        </div>
    </AuthenticatedLayout>
</template>



<script setup>
import {Head, Link} from "@inertiajs/vue3";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import {ArrowSmallLeftIcon} from "@heroicons/vue/24/outline/index.js";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import FooterIndex from "@/Layouts/Molecules/FooterIndex.vue";
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";

function printPage() {
    window.print();
}

defineProps({
    microsite: {
        type: Array,
        default: () => []
    },
    subscription: {
        type: Array,
        default: () => []
    },
    subscriptionPlans:{
        type: Array,
        default: () => []
    }
});

const statusColors = {
    PENDING: 'yellow',
    ACTIVE: 'green',
    CANCELLED: 'red',
    PAUSED: 'cyan',
    REJECTED: 'orange',
    EXPIRED: 'blue'
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        timeZoneName: 'short'
    });
};

</script>

<template>
    <Head :title="$t('voucher.label')"/>
    <header class="bg-white rounded-md w-full flex justify-between items-center p-6">
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

    <main class="flex-grow flex items-center justify-center bg-white">
        <div id="form" class="bg-white px-8 py-4 rounded-3xl border border-gray-300 w-full max-w-2xl shadow-2xl">
            <div class="flex justify-between items-center m-5 ">
                <div class="text-3xl">
                    <span class="block font-extrabold">{{ $t('voucher.label') }}</span>
                    <span class="block"> {{ $t('voucher.subscription') }}</span>
                </div>
                <div class="">
                    <img v-if="microsite.logo" class=" rounded-lg h-28 object-contain" :src="`/storage/${microsite.logo}`" alt="Logo">
                </div>
            </div>


                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-5">
                    <!-- Nombre del usuario -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.subscriptionMade') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ subscription.name }} {{ subscription.surname }}
                        </dd>
                    </div>

                    <!-- Número de factura -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.InvoiceNumber') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ subscription.request_id }}
                        </dd>
                    </div>

                    <!-- Información del plan -->
                    <div class="pb-2">
                        <dt class="text-lg font-medium text-gray-400">{{ $t('voucher.planInformation') }}</dt>
                        <dd class="text-lg text-gray-700 font-semibold">
                            {{ subscription.description }}
                        </dd>
                    </div>

                    <!-- Referencia -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.Reference') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ subscription.reference }}
                        </dd>
                    </div>

                    <!-- Fecha de creación -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.date') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ formatDate(subscription.created_at) }}
                        </dd>
                    </div>

                    <!-- Nombre del plan -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.planName') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ subscriptionPlans[0]?.name }}
                        </dd>
                    </div>

                    <!-- Periodo de suscripción -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.subscriptionPeriod') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ subscriptionPlans[0]?.expiration_time }}
                        </dd>
                    </div>

                    <!-- Periodos de suscripción -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.periodCount') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ $t(`subscription.${subscriptionPlans[0]?.subscription_period}`) }}
                        </dd>
                    </div>

                    <!-- Moneda -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.currency') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ $t(`currencies.${subscriptionPlans[0]?.currency}`) }}
                        </dd>
                    </div>

                    <!-- Monto pagado -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.paid') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ subscriptionPlans[0]?.currency === 'USD'
                            ? `$ ${subscriptionPlans[0]?.amount.toLocaleString('en-US')}`
                            : `$ ${subscriptionPlans[0]?.amount.toLocaleString('es-CO')}` }}
                        </dd>
                    </div>

                    <!-- Producto (Micrositio) -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.product') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            {{ microsite.name }}
                        </dd>
                    </div>

                    <!-- Estado de la suscripción -->
                    <div class="pb-2">
                        <dt class="text-md font-medium text-gray-400">{{ $t('voucher.state') }}</dt>
                        <dd class="text-md text-gray-700 font-semibold">
                            <span-form :color="statusColors[subscription.status]">
                                {{ $t(`payment_status.${subscription.status}`) }}
                            </span-form>
                        </dd>
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
                        {{ $t('voucher.print') }}
                    </button>
                </div>
            </div>

    </main>

    <FooterIndex />
</template>

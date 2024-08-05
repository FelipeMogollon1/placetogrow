<script setup>
import { Link } from "@inertiajs/vue3";
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
    payment: {
        type: Array,
        default: () => []
    }
});

const statusColors = {
    PENDING: 'yellow',
    APPROVED: 'green',
    REJECTED: 'red',
    APPROVED_PARTIAL: 'cyan',
    PARTIAL_EXPIRED: 'orange',
    UNKNOWN: 'blue'
};

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
                <span class="block font-extrabold">{{ $t('voucher.label') }}</span>
                <span class="block"> {{ $t('voucher.pay') }}</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-2 gap-4 m-5 ">
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.PaymentMade') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.payer_name }} {{ payment.payer_surname }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.InvoiceNumber') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.request_id }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.PaymentDescription') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.description }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.Reference') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.reference }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.date') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.created_at }}</dd>
                    </div>

                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.currency') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.currency }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.paid') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ payment.amount }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.product') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">{{ microsite.name }}</dd>
                    </div>
                    <div class="pb-1">
                        <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('voucher.state') }}</dt>
                        <dd class="text-sm leading-6 text-gray-700 font-semibold">
                            <span-form :color="statusColors[payment.status]">
                                {{ $t(`payment_status.${payment.status}`) }}
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
                        Imprimir Comprobante
                    </button>
                </div>
            </div>

    </main>

    <FooterIndex />
</template>

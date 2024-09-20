<template>
    <div>
        <div class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('dashboard')">
                <PresentationChartBarIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{ $t('dashboard') }}</div>
            </Link>
        </div>

        <div v-if="can('microsites.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('microsites.index')">
                <GlobeAmericasIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{ $t('microsites') }}</div>
            </Link>
        </div>

        <div v-if="can('categories.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('categories.index')">
                <SwatchIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{$t('categories')}}</div>
            </Link>
        </div>

        <div v-if="can('users.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('users.index')">
                <UserGroupIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{$t('user.label')}}</div>
            </Link>
        </div>

        <div v-if="can('roles.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('roles.index')">
                <QueueListIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{ $t('roles') }}</div>
            </Link>
        </div>

        <div v-if="can('payments.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('payments.index')">
                <BanknotesIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{ $t('payment.payments_title') }}</div>
            </Link>
        </div>

        <div v-if="can('subscriptions.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('subscriptions.index')">
                <ArrowPathRoundedSquareIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{ $t('subscription.subscriptions') }}</div>
            </Link>
        </div>

        <div v-if="can('invoices.index')" class="mb-4">
            <Link class="group flex items-center p-2 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out" :href="route('invoices.index')">
                <DocumentArrowUpIcon class="mr-3 h-6 w-6 text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out" />
                <div class="text-gray-500 group-hover:text-orange-500 transition duration-150 ease-in-out">{{ $t('invoices.title') }}</div>
            </Link>
        </div>
    </div>
</template>

<script>
import {
    MagnifyingGlassIcon,
    PresentationChartBarIcon,
    GlobeAmericasIcon,
    SwatchIcon,
    UserGroupIcon,
    QueueListIcon,
    BanknotesIcon,
    ArrowPathRoundedSquareIcon,
    DocumentArrowUpIcon
} from "@heroicons/vue/24/outline/index.js";
import { Link } from '@inertiajs/vue3'

export default {
    components: {
        MagnifyingGlassIcon,
        PresentationChartBarIcon,
        GlobeAmericasIcon,
        SwatchIcon,
        UserGroupIcon,
        QueueListIcon,
        BanknotesIcon,
        ArrowPathRoundedSquareIcon,
        DocumentArrowUpIcon,
        Link,
    }
}
</script>

<template>
    <div>
        <div  class="mb-4">
            <Link class="group flex items-center py-1" :href="route('dashboard')">
                <PresentationChartBarIcon class="mr-2 h-6 text-gray-500 group-hover:text-orange-500" />
                <div class="text-gray-500 group-hover:text-orange-500">{{ $t('dashboard') }}</div>
            </Link>
        </div>

        <div v-if="can('microsites.index')" class="mb-4">
            <Link class="group flex items-center py-1" :href="route('microsites.index')">
                <GlobeAmericasIcon class="mr-2 h-6 text-gray-500 group-hover:text-orange-500" />
                <div class="text-gray-500 group-hover:text-orange-500">{{ $t('microsites') }}</div>
            </Link>
        </div>

        <div v-if="can('categories.index')" class="mb-4">
            <Link class="group flex items-center py-1" :href="route('categories.index')">
                <SwatchIcon class="mr-2 h-6 text-gray-500 group-hover:text-orange-500" />
                <div class="text-gray-500 group-hover:text-orange-500">{{$t('categories')}}</div>
            </Link>
        </div>

        <div v-if="can('users.index')" class="mb-4">
            <Link class="group flex items-center py-1" :href="route('users.index')">
                <UserGroupIcon class="mr-2 h-6 text-gray-500 group-hover:text-orange-500" />
                <div class="text-gray-500 group-hover:text-orange-500">{{$t('user.label')}}</div>
            </Link>
        </div>

        <div v-if="can('roles.index')" class="mb-4">
            <Link class="group flex items-center py-1" :href="route('roles.index')">
                <QueueListIcon class="mr-2 h-6 text-gray-500 group-hover:text-orange-500" />
                <div class="text-gray-500 group-hover:text-orange-500">{{ $t('roles') }}</div>
            </Link>
        </div>

        <div v-if="can('payments.index')" class="mb-4">
            <Link class="group flex items-center py-1" :href="route('payments.index')">
                <BanknotesIcon class="mr-2 h-6 text-gray-500 group-hover:text-orange-500" />
                <div class="text-gray-500 group-hover:text-orange-500">{{ $t('payment.payments_title') }}</div>
            </Link>
        </div>

    </div>
</template>

<script>
import {MagnifyingGlassIcon, PresentationChartBarIcon, GlobeAmericasIcon, SwatchIcon, UserGroupIcon, QueueListIcon, BanknotesIcon} from "@heroicons/vue/24/outline/index.js";
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
        Link,
    }
}
</script>

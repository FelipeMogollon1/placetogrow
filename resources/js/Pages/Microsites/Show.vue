<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import {ref} from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";

const page = usePage();
const microsite = ref(page.props.microsite);

</script>

<template>
    <Head :title="$t('detail_microsite')"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('detail_microsite') }}</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('list_microsites') }}
                </Link>
            </div>

            <div class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-lg">
                <div class="mt-6 border-t border-gray-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('microsites_table.slug') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.slug }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('microsites_table.name') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.name }}</dd>
                        </div>
                        <div v-if="microsite.user_id !== null" class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('roles_table.admin') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.user.name }}</dd>
                        </div>

                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.type') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`micrositeTypes.${microsite.microsite_type}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.document_type') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`documentType.${microsite.document_type}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.document') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.document }}</dd>
                        </div>

                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('currency') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`currencies.${microsite.currency}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('payment_expiration_time') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.payment_expiration_time }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.category') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.category.name }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.category_description') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.category.description }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.date') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.enabled_at }}</dd>
                        </div>
                        <div v-if="microsite.logo" class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.logo') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">
                                <img class="w-20 h-20 rounded-full shadow" :src="`/storage/${microsite.logo}`" alt="logo">
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('form') }}</h2>
            </div>

            <div class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-lg">
                <div class="mt-6 border-t border-gray-100">
                    hola
                </div>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

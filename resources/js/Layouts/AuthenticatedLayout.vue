<script setup>
import {ref, watchEffect} from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import FlashMessages from '@/Layouts/Molecules/FlashMessages.vue';
import {Link, usePage} from '@inertiajs/vue3';
import MainMenu from "@/Layouts/Molecules/MainMenu.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import LanguageDropdown from "@/Layouts/Atoms/LanguageDropdown.vue";
import {ListBulletIcon, ChevronDownIcon} from "@heroicons/vue/24/outline/index.js";


const page = usePage();
const props = ref(page.props);
watchEffect(() => {
    window.Laravel = window.Laravel || {}
    window.Laravel.jsPermissions = props.value.auth.permissionsJs ?? {};
})

</script>

<template>
    <div>
        <div id="dropdown" />
        <div class="md:flex md:flex-col">
            <div class="md:flex md:flex-col md:h-screen">
                <div class="md:flex md:shrink-0">
                    <div class="flex items-center justify-between px-4 py-2 bg-white md:shrink-0 md:justify-center md:w-56">
                        <ApplicationLogo/>
                        <dropdown class="md:hidden" placement="bottom-end">
                            <template #default>
                                <ListBulletIcon class="w-6 h-6 fill-gray-700 group-hover:fill-orange-600"/>
                            </template>
                            <template #dropdown>
                                <div class="mt-2 px-6 py-2 bg-white rounded shadow-lg">
                                    <MainMenu/>
                                </div>
                            </template>
                        </dropdown>
                    </div>

                    <div class="md:text-md flex items-center justify-between p-3 w-full text-sm bg-white border-b md:px-8 md:py-1">
                        <div class="mr-2 mt-1 capitalize">{{ $t(`roles_table.${props.auth.user.roles[0].name}`) }} </div>
                        <LanguageDropdown />

                        <dropdown class="mt-1" placement="bottom-end">

                            <template #default>
                                <div class="group flex items-center cursor-pointer select-none">
                                    <div class="mr-1 text-gray-500 group-hover:text-gray-700 focus:text-gray-900 whitespace-nowrap">
                                        <span class="capitalize">{{props.auth.user.name }}</span>
                                    </div>
                                    <ChevronDownIcon class="w-4 h-4 text-gray-500 group-hover:text-gris-700 focus:text-gris-900"/>
                                </div>
                            </template>

                            <template #dropdown>
                                <div class="mt-2 py-2 text-sm bg-white rounded shadow-xl">
                                    <Link class="block px-6 py-2 hover:text-white hover:bg-orange-500" :href="route('profile.edit')">
                                        {{ $t('user.profile') }}</Link>
                                    <Link class="block px-6 py-2 w-full text-left hover:text-white hover:bg-orange-500" :href="route('logout')" method="post" as="button">
                                        {{ $t('logout') }}</Link>
                                </div>
                            </template>

                        </dropdown>
                    </div>

                </div>

                <div class="md:flex md:grow md:overflow-hidden">
                    <main-menu class="hidden shrink-0 p-8 w-56 bg-white overflow-y-auto md:block border border-1" />
                    <div class="px-4 py-4 md:flex-1 md:p-8 md:overflow-y-auto bg-gray-50" scroll-region>
                        <slot name="header" />
                        <flash-messages />
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

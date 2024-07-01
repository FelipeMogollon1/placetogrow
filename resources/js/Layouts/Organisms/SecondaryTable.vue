<script setup>
import {defineProps, ref} from 'vue';
import {Link, usePage} from "@inertiajs/vue3";
import { route } from "ziggy-js";
import {TrashIcon, PencilIcon, EyeIcon} from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    headers: {
        type: Array,
        required: true
    }
});
const page = usePage()
const microsites = ref(page.props.microsites)

const onDeleteSuccess = (e) => {
   microsites.value = e.props.microsites
}



</script>

<template>
    <div class="flex flex-col m-10">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-2xl">
                    <table class="min-w-full ">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th v-for="header in headers" :key="header" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ header }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Logo</th>
                            <th class="flex justify-center  px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in data" :key="index" class="transition duration-300 ease-in-out hover:bg-gray-100">

                            <template v-for="(value, key) in item">

                                <template v-if="key !== 'logo' && key !== 'id'">
                                    <td :key="key" class="px-6 py-3 whitespace text-sm text-gray-900">
                                        {{ value }}
                                    </td>
                                </template>
                            </template>

                            <td class="px-6 py-4 whitespace text-sm text-gray-900">
                                <div v-if="item.logo">
                                    <img class="max-w-15 max-h-14" :src="`/storage/${item.logo}`" alt="logo">
                                </div>
                            </td>

                            <td>
                                <div class="text-gray-400 flex justify-center  ">
                                    <Link v-if="can('microsites.edit')" :href="route('microsites.edit', item.id)">
                                       <PencilIcon class="w-6 hover:text-gray-500"/>
                                    </Link>

                                    <Link  v-if="can('microsites.show')"  class="mx-1" :href="route('microsites.show', item.id)">
                                        <EyeIcon class="w-6 hover:text-gray-500"/>
                                    </Link>

                                    <Link
                                        v-if="can('microsites.destroy')"
                                        @succes="onDeleteSuccess"
                                        :href="route('microsites.destroy', item.id)"
                                        method="delete"
                                        as="button"
                                    >
                                        <TrashIcon class="w-6 hover:text-red-500" />
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
</template>

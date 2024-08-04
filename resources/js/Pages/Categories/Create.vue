<script setup>
import { Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const initialValues = {
    name : "",
    description : ""
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('categories.store'))
}

</script>

<template>

    <Head :title="$t('category.create_category')"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{$t('category.create_category')}}</h2>
                <Link
                    v-if="can('categories.index')"
                    :href="route('categories.index')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('category.list_category') }}
                </Link>
            </div>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden border border-gray-100 sm:rounded-lg shadow-2xl">
                    <div class="p-6 text-gray-900 ">
                        <form class="grid grid-cols-1 gap-6 sm:grid-cols-2 " @submit.prevent="submit">
                        <div>
                            <InputLabel for="name" :value="$t('category.name')" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.name"
                                autofocus
                                autocomplete="name"
                                :placeholder="$t('category.name')"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="description" :value="$t('category.description')"/>
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                autofocus
                                autocomplete="name"
                                :placeholder="$t('category.description')"
                            />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="flex justify-center col-span-2">
                            <PrimaryButton >
                                {{$t('category.create_category')}}
                            </PrimaryButton>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

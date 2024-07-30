<script setup>
import { Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";

const initialValues = {
    name : "",
    logo : null,
    document_type:"",
    document:"",
    microsite_type:"",
    currency:"",
    payment_expiration_time:"",
    category_id:"",
    user_id:""
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('microsites.store'))
}

const onSelectLogo = (e) => {
    const files = e.target.files
    if(files.length){
        form.logo = files[0]
    }
    console.log(form.logo)
}

defineProps({
    documentTypes: {
        type: Array,
        default: () => []
    },
    micrositesTypes: {
        type: Array,
        default: () => []
    },
    currencyTypes: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
    users: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <Head :title="$t('create_microsite')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('create_microsite') }}</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('list_microsites') }}
                </Link>
            </div>
        </template>

        <div class="pt-2 ">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <InputLabel for="name" :value="$t('microsites_table.name')" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Software"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="user_id" :value="$t('roles_table.admin')" />
                                <select
                                    name="user_id"
                                    id="user_id"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.user_id"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.user_id" />
                            </div>

                            <div>
                                <InputLabel for="document_type" :value="$t('microsites_table.document_type')" />
                                <select
                                    name="document_type"
                                    id="document_type"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.document_type"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(type, index) in documentTypes" :key="index" :value="type">{{ $t(`documentType.${type}`) }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.document_type" />
                            </div>

                            <div>
                                <InputLabel for="document" :value="$t('microsites_table.document')" />
                                <TextInput
                                    id="document"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.document"
                                    autofocus
                                    autocomplete="document"
                                    placeholder="1234567890"
                                />
                                <InputError class="mt-2" :message="form.errors.document" />
                            </div>

                            <div>
                                <InputLabel for="microsite_type" :value="$t('microsites_table.microsite_type')" />
                                <select
                                    name="microsite_type"
                                    id="microsite_type"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.microsite_type"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(type, index) in micrositesTypes" :key="index" :value="type">{{ $t(`micrositeTypes.${type}`) }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.microsite_type" />
                            </div>

                            <div>
                                <InputLabel for="currency" :value="$t('currency')" />
                                <select
                                    name="currency"
                                    id="currencyTypes"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.currency"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(type, index) in currencyTypes" :key="index" :value="type">{{ $t(`currencies.${type}`) }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.currency" />
                            </div>

                            <div>
                                <InputLabel for="payment_expiration_time" :value="$t('microsites_table.time_expiration')" />
                                <TextInput
                                    id="payment_expiration_time"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="form.payment_expiration_time"
                                    min="1"
                                    max="18000000000000000000"
                                    autofocus
                                    autocomplete="payment_expiration_time"
                                    placeholder="1234567890"
                                />
                                <InputError class="mt-2" :message="form.errors.payment_expiration_time" />
                            </div>

                            <div>
                                <InputLabel for="category_id" :value="$t('microsites_table.category')" />
                                <select
                                    name="category_id"
                                    id="category_id"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.category_id"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>

                            <div>
                                <InputLabel for="logo" :value="$t('microsites_table.logo')" />
                                <FileInput name="logo" @change="onSelectLogo" />
                                <InputError class="mt-2" :message="form.errors.logo" />
                            </div>

                            <div class="col-span-2 flex justify-center">
                                <PrimaryButton>
                                    {{ $t('create_microsite') }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

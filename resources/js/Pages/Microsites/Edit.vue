<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";

const props = defineProps({
    arrayConstants: {
        type: Object,
        default: () => []
    },
    microsite: {
        type: Object,
        default: () => []
    }
});


const initialValues = {
    name : props.microsite.name,
    logo : null,
    document_type: props.microsite.document_type,
    document: props.microsite.document,
    microsite_type: props.microsite.microsite_type,
    currency: props.microsite.currency,
    payment_expiration_time: props.microsite.payment_expiration_time,
    category_id: props.microsite.category_id,
    user_id: props.microsite.user_id,
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('microsites.custom_update', props.microsite.id))
}

const onSelectLogo = (e) => {
    const files = e.target.files
    if(files.length){
        form.logo = files[0]
    }
}

</script>

<template>
    <Head :title="$t('updateMicrosite')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('updateMicrosite') }}</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('list_microsites') }}
                </Link>
            </div>
        </template>

        <div class="pt-6">
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
                                    placeholder="Tecnología"
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
                                    <option v-for="user in props.arrayConstants.users" :key="user.id" :value="user.id">{{ user.name }}</option>
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
                                    <option v-for="(type, key) in props.arrayConstants.documentTypes" :key="key" :value="type">{{ $t(`documentType.${type}`) }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.document_type" />
                            </div>

                            <div>
                                <InputLabel for="document" value="Número de documento" />
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
                                <InputLabel for="microsite_type" value="Tipo de Micrositio" />
                                <select
                                    name="microsite_type"
                                    id="microsite_type"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.microsite_type"
                                >
                                    <option value="">{{ $t('select') }}</option>
                                    <option v-for="(type, key) in props.arrayConstants.micrositesTypes" :key="key" :value="type">{{ $t(`micrositeTypes.${type}`) }}</option>
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
                                    <option v-for="(type, key) in props.arrayConstants.currencyTypes" :key="key" :value="type">{{ $t(`currencies.${type}`) }}</option>
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
                                    autofocus
                                    autocomplete="payment_expiration_time"
                                    placeholder="123"
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
                                    <option v-for="category in props.arrayConstants.categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>

                            <div>
                                <InputLabel for="logo" :value="$t('microsites_table.logo')" />
                                <FileInput name="logo" @change="onSelectLogo" />
                                <InputError class="mt-2" :message="form.errors.logo" />
                            </div>

                            <div v-if="props.microsite.logo">
                                <img class="max-w-full max-h-16 rounded-lg" :src="`/storage/${props.microsite.logo}`" alt="logo" />
                            </div>

                            <div class="col-span-1 sm:col-span-2 flex justify-center">
                                <PrimaryButton>
                                    {{ $t('updateMicrosite') }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


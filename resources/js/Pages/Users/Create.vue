<script setup>
import { Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {watch} from "vue";

const initialValues = {
    name : "",
    surname : "",
    document_type:"",
    document:"",
    email : "",
    password: "",
    password_confirmation: "",
    role: ""
}

const form = useForm(initialValues)

const documentPatterns = {
    CC: /^[1-9][0-9]{3,9}$/,
    CE: /^([a-zA-Z]{1,5})?[1-9][0-9]{3,7}$/,
    TI: /^[1-9][0-9]{4,11}$/,
    NIT: /^[1-9]\d{6,9}$/,
};

watch(() => form.document_type, (newType) => {
    if (newType && form.document) {
        validateDocument(form.document, newType);
    }
});

const validateDocument = (document, type) => {
    const pattern = documentPatterns[type];
    if (pattern && !pattern.test(document)) {
        form.errors.document = `El documento no es válido para el tipo ${type}.`;
    } else {
        form.errors.document = null;
    }
};

const submit = () => {

    validateDocument(form.document, form.document_type);

    if (!form.errors.document) {
        form.post(route('users.store'));
    }
}

const props = defineProps({
    roles: {
        type:Object
    },
    documentTypes: {
        type: Object
    }
})

</script>

<template>
    <Head :title="$t('user.create_user')"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('user.create_user') }}</h2>
                <Link
                    :href="route('users.index')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('user.list_users') }}
                </Link>
            </div>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden border border-gray-100 sm:rounded-lg shadow-2xl">
                    <div class="p-6 text-gray-900 ">
                        <form class="grid grid-cols-1 gap-6 sm:grid-cols-2 " @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" :value="$t('user.name')"  />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="name"
                                    :placeholder="$t('user.name')"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div>
                                <InputLabel for="surname" :value="$t('user.surname')"  />
                                <TextInput
                                    id="surname"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.surname"
                                    autofocus
                                    autocomplete="surname"
                                    :placeholder="$t('user.name')"
                                />
                                <InputError class="mt-2" :message="form.errors.surname" />
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
                                <InputLabel for="email" :value="$t('user.email')" />
                                <TextInput
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    autofocus
                                    autocomplete="email"
                                    placeholder="test@mail.com"
                                />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div>
                                <InputLabel for="role" value="Roles" />

                                <select name="role"
                                        id="role"
                                        v-model="form.role"
                                        class="w-full mt-1 border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-md shadow-sm"
                                >

                                    <option value="">{{$t('select')}}</option>
                                    <option
                                        v-for="role in props.roles"
                                            :value="role.name"
                                    >
                                        <template class="capitalize" v-if="['admin', 'guest', 'sa'].includes(role.name)">
                                            {{ $t(`roles_table.${role.name}`) }}
                                        </template>

                                        <template class="capitalize" v-else>
                                            {{ role.name }}
                                        </template>

                                    </option>

                                </select>
                                <InputError class="mt-2" :message="form.errors.role" />
                            </div>

                            <div>
                                <InputLabel for="password" :value="$t('user.password')" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    autofocus
                                    autocomplete="password"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>


                            <div>
                                <InputLabel for="password" :value="$t('user.confirm_password')" />
                                <TextInput
                                    id="password_confirmation"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password_confirmation"
                                    autofocus
                                    autocomplete="password"
                                />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>

                            <div class="col-span-2 flex justify-center">
                                <PrimaryButton >
                                    {{ $t('user.create_user') }}
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

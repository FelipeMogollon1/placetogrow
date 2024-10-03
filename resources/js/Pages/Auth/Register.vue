<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {watch} from "vue";
import {route} from "ziggy-js";

const props = defineProps({
    documentTypes: {
        type: Object
    },
    roleGuest: {
        type: String
    }
})

const initialValues = {
    name: "",
    surname: "",
    document_type: "",
    document: "",
    email: "",
    password: "",
    password_confirmation: "",
    role: props.roleGuest
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
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    }
};

</script>

<template>
    <GuestLayout>
        <Head :title="$t('register')"/>

        <form @submit.prevent="submit">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div>
                    <InputLabel for="name" :value="$t('user.name')"/>
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name"/>
                </div>


                <div>
                    <InputLabel for="surname" :value="$t('user.surname')"/>
                    <TextInput
                        id="surname"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.surname"
                        required
                        autofocus
                        autocomplete="surname"
                        :placeholder="$t('user.name')"
                    />
                    <InputError class="mt-2" :message="form.errors.surname"/>
                </div>


                <div >
                    <InputLabel for="document_type" :value="$t('microsites_table.document_type')"/>
                    <select
                        name="document_type"
                        id="document_type"
                        class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.document_type"
                        required
                        autofocus
                    >
                        <option value="">{{ $t('select') }}</option>
                        <option v-for="(type, index) in documentTypes" :key="index" :value="type">
                            {{ $t(`documentType.${type}`) }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.document_type"/>
                </div>


                <div>
                    <InputLabel for="document" :value="$t('microsites_table.document')"/>
                    <TextInput
                        id="document"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.document"
                        required
                        autofocus
                        autocomplete="document"
                        placeholder="1234567890"
                    />
                    <InputError class="mt-2" :message="form.errors.document"/>
                </div>


                <div class="col-span-2 ">
                    <InputLabel for="email" :value="$t('user.email')"/>
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="email"
                    />
                    <InputError class="mt-2" :message="form.errors.email"/>
                </div>


                <div>
                    <InputLabel for="password" :value="$t('user.password')"/>
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autofocus
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password"/>
                </div>


                <div>
                    <InputLabel for="password_confirmation" :value="$t('user.confirm_password')"/>
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autofocus
                        autocomplete="new-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password_confirmation"/>
                </div>
            </div>


            <div class="flex items-center justify-end mt-6 space-x-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ $t('user.already_registered') }}
                </Link>

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ $t('register') }}
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>

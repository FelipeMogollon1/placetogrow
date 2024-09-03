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
    email : "",
    password: "",
    password_confirmation: "",
    role: ""
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('users.store'))
}

const props = defineProps({
    roles: { type:Object}
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

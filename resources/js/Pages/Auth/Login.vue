<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {route} from "ziggy-js";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: true,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head :title="$t('login')" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="max-w-md mx-auto p-6 bg-white">
            <h2 class="text-xl font-semibold text-center mb-4">{{ $t('login') }}</h2>

            <div>
                <InputLabel for="email" :value="$t('user.email')" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" :value="$t('user.password')" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-orange-500"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ $t('remember_me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-center mt-4">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                >
                    {{$t('forgot_your_password')}}
                </Link>
            </div>

            <div class="flex items-center justify-center mt-4">

                <PrimaryButton class="ms-4 bg-orange-500 hover:bg-orange-600 transition duration-200" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{$t('login')}}
                </PrimaryButton>
            </div>

            <div class="mt-4 text-center">
                <span class="text-sm text-gray-600">{{ $t('no_account') }}</span>
                <Link
                    :href="route('register')"
                    class="ml-1 text-orange-500 hover:underline"
                >
                    {{ $t('register_here') }}
                </Link>
            </div>
        </form>

    </GuestLayout>
</template>

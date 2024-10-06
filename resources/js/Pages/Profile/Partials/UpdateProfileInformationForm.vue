<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    documentTypes: {
        type: Object,
        default: () => {}
    }
});

const user = usePage().props.auth.user;
const documentTypes = usePage().props.documentTypes;

const form = useForm({
    name: user.name,
    email: user.email,
    surname: user.surname,
    document_type: user.document_type,
    document: user.document,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">{{ $t('user.profile_information') }}</h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ $t('user.profile_information_additional') }}
            </p>
        </header>
        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="name" :value="$t('user.name')" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="surname" :value="$t('user.surname')" />
                    <TextInput
                        id="surname"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.surname"
                        required
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
                        required
                        autocomplete="document"
                        placeholder="1234567890"
                    />
                    <InputError class="mt-2" :message="form.errors.document" />
                </div>

                <div class="col-span-2 ">
                    <InputLabel for="email" :value="$t('user.email')" />
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 ">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>
                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">{{ $t('save') }}</PrimaryButton>
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">{{ $t('saved') }}</p>
                </Transition>
            </div>
        </form>
    </section>
</template>

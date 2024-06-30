<script setup>
import { Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {defineProps} from "vue";

const props = defineProps({
    roles:{
        type:Object
    }
})

const initialValues = {
    name : props.roles.name,
}

const form = useForm(initialValues)

const submit = () => {
    form.put(route('roles.update',props.roles.id))
}

</script>

<template>
    <Head title="Actualizar categoria"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Actualizar Rol</h2>
                <Link
                    :href="route('roles.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Lista de roles
                </Link>
            </div>
        </template>


        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-center p-6 text-gray-900">
                        <form class="w-1/3 py-8 space-y-5" @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Pedro Jose"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                             <div class="flex justify-center">
                                <PrimaryButton >
                                    Actualizar Rol
                                </PrimaryButton>
                            </div>
                            <div class="flex justify-center">

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    category:{
        type:Object
    }
})

const initialValues = {
    name : props.category.name,
    description : props.category.description
}

const form = useForm(initialValues)

const submit = () => {
    form.put(route('categories.update',props.category.id))
}

</script>

<template>

    <Head title="Actualizar categoria"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Actualizar Categoria</h2>
                <Link
                    :href="route('categories.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Lista de categorias
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
                                placeholder="Tecnología"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="description" value="Descripción" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.description"
                                autofocus
                                autocomplete="name"
                                placeholder="Tienda de videojuegos y Computadores"
                            />
                            <InputError class="mt-2" :message="form.errors.description" />
                        </div>

                        <div class="flex justify-center">
                            <PrimaryButton >
                                Actualizar Categoria
                            </PrimaryButton>
                        </div>
                        <div class="flex justify-center">
                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Actualización Satisfactoria.</p>
                            </Transition>
                        </div>
                    </form>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

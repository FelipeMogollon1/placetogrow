<script setup>
import {Head, Link, useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileInput from "@/Components/FileInput.vue";
import {defineProps, ref} from "vue";

defineProps({
    documentTypes: {
        type: Object,
        default: () => []
    },
    micrositesTypes: {
        type: Object,
        default: () => []
    },
    currencyTypes: {
        type: Object,
        default: () => []
    },
    categories: {
        type: Object,
        default: () => []
    }
});

const page = usePage()
const microsite = ref(page.props.microsite);

const initialValues = {
    name : microsite.value.name,
    logo : null,
    document_type: microsite.value.document_type,
    document: microsite.value.document,
    microsite_type: microsite.value.microsite_type,
    currency: microsite.value.currency,
    payment_expiration_time: microsite.value.payment_expiration_time,
    category_id: microsite.value.category_id
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('microsites.update',microsite.value),{
        onSuccess: (e) => {
            microsite.value = e.props.microsite
        }
    })

}

const onSelectLogo = (e) => {
    const files = e.target.files
    if(files.length){
        form.logo = files[0]
    }
}


</script>

<template>
    <Head title="Actualizar Micrositio"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Actualizar Micrositio</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Lista de Micrositios
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-center p-6 text-gray-900">
                        <form class="w-1/3 py-8 space-y-5" @submit.prevent="submit">

                            <div class="flex justify-center">
                                <Transition
                                    enter-active-class="transition ease-in-out"
                                    enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out"
                                    leave-to-class="opacity-0"
                                >
                                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Actualización del micrositio Satisfactoria.</p>
                                </Transition>
                            </div>

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
                            <img class="max-w-full max-h-16 " :src="`/storage/${microsite.logo}`" alt="logo">
                        </div>

                        <div>
                            <InputLabel for="logo" value="Logo" />
                            <FileInput name="logo" @change="onSelectLogo"/>
                            <InputError class="mt-2" :message="form.errors.logo" />
                        </div>

                        <div>
                            <InputLabel for="document_type" value="Tipo de Documento" />
                            <select
                                name="document_type"
                                id="document_type"
                                class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.document_type"
                            >
                                <option value="">Seleccione</option>
                                <option v-for="(type, key) in documentTypes" :key="key" :value="key">{{ type }}</option>
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
                                <option value="">Seleccione</option>
                                <option v-for="(type, key) in micrositesTypes" :key="key" :value="type">{{ type }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.microsite_type" />
                        </div>

                        <div>
                            <InputLabel for="currency" value="Moneda" />
                            <select
                                name="currency"
                                id="currencyTypes"
                                class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                v-model="form.currency"
                            >
                                <option value="">Seleccione</option>
                                <option v-for="(type, key) in currencyTypes" :key="key" :value="key">{{ type }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.currency" />
                        </div>

                        <div>
                            <InputLabel for="payment_expiration_time" value="Tiempo de expiración en segundos" />
                            <TextInput
                                id="payment_expiration_time"
                                type="number"
                                class="mt-1 block w-full"
                                v-model="form.payment_expiration_time"
                                autofocus
                                autocomplete="payment_expiration_time"
                                placeholder="1234567890"
                            />
                            <InputError class="mt-2" :message="form.errors.payment_expiration_time" />
                        </div>

                            <div>
                                <InputLabel for="category_id" value="Categoria" />
                                <select
                                    name="category_id"
                                    id="category_id"
                                    class="w-full mt-1 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="form.category_id"
                                >
                                    <option value="">Seleccione</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.category_id" />
                            </div>
                        <div class="flex justify-center">
                            <PrimaryButton >
                                Actualizar Micrositio
                            </PrimaryButton>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

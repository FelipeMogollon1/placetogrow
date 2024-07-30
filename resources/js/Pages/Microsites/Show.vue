<script setup>
import { ref, reactive, watch } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { route } from 'ziggy-js';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputComponent from '@/Layouts/Organisms/InputComponent.vue';

const page = usePage();
const microsite = ref(page.props.microsite || {});
const formConfig = ref(page.props.configuration[0] || {});
const documentTypes = ref(page.props.documentTypes || []);

const form = useForm({
    id: formConfig.value.id,
    configuration: formConfig.value.configuration
});

const formErrors = ref(form.errors);

const updateFieldValue = (name, value) => {
    form.configuration.fields = form.configuration.fields.map(field =>
        field.name === name ? { ...field, value } : field
    );
};


const toggleFieldActive = (field) => {
    field.active = field.active === 'true' ? 'false' : 'true';

    form.configuration.fields = form.configuration.fields.map(f =>
        f.name === field.name ? { ...f, active: field.active } : f
    );
};


const submit = () => {
    form.put(route('forms.update', { id: form.id }), {
        onError: (errors) => {
            console.log(errors);
        },
    });
};

watch(() => form.configuration, (newConfig) => {
    form.configuration = newConfig;
}, { deep: true });



</script>


<template>
    <Head :title="$t('detail_microsite')"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('detail_microsite') }}</h2>
                <Link
                    :href="route('microsites.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('list_microsites') }}
                </Link>
            </div>

            <div class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-lg">
                <div class="mt-6 border-t border-gray-100">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('microsites_table.slug') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.slug }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{ $t('microsites_table.name') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.name }}</dd>
                        </div>
                        <div v-if="microsite.user_id !== null" class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('roles_table.admin') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.user.name }}</dd>
                        </div>

                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.type') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`micrositeTypes.${microsite.microsite_type}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.document_type') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`documentType.${microsite.document_type}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.document') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.document }}</dd>
                        </div>

                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('currency') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`currencies.${microsite.currency}`) }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('payment_expiration_time') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.payment_expiration_time }}</dd>
                        </div>
                        <div class="px-4 pt-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.category') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.category.name }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.category_description') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.category.description }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.date') }} :</dt>
                            <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.enabled_at }}</dd>
                        </div>
                        <div class="px-4 py-3">
                            <dt class="text-sm font-medium leading-6 text-gray-400">{{$t('microsites_table.logo') }} :</dt>
                            <dd v-if="microsite.logo" class="text-md leading-6 text-gray-700 font-semibold">
                                <img class="w-20 h-20 rounded-full shadow" :src="`/storage/${microsite.logo}`" alt="logo">
                            </dd>
                            <PhotoIcon v-else class="w-16 h-16 text-gray-400 object-contain hover:text-gray-600"/>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex justify-between items-center pt-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('form') }}</h2>
            </div>

            <main>
                <div class="flex justify-center items-center ">
                    <div id="form" class="container bg-white grid grid-cols-2 md:grid-cols-2 gap-4 sm:grid-cols-1 p-4 m-4 rounded-2xl shadow-lg">
                        <div class="col-span-2 flex items-center rounded-lg bg-gray-100 justify-center py-2">
                            <p class="text-gray-400 text-lg px-4">Seleccione una cabecera</p>
                            <PhotoIcon class="w-12 h-12 text-gray-400"/>
                        </div>
                        <div class="col-span-2 flex">
                            <div class="text-lg font-semibold">
                                Pagos electrónicos
                            </div>
                        </div>
                        <div class="col-span-2 flex">
                            <div class="text-md pb-2">
                                Información adicional
                            </div>
                        </div>

                        <template v-for="field in formConfig.configuration.fields" :key="field.name">
                            <div v-if="field.active === 'true'" class="mb-2">
                                <InputComponent
                                    :type="field.type"
                                    :name="field.name"
                                    :modelValue="form[field.name]"
                                    @update:modelValue="updateFieldValue(field.name, $event)"
                                    :placeholder="field.name"
                                    :label="field.name"
                                    :errorMessage="formErrors[field.name]"
                                    :options="field.options"
                                    :constants="documentTypes"
                                    inputClass="w-full mt-1 text-sm border-gray-300 rounded-md py-1 px-2"
                                />
                            </div>
                        </template>

                        <div class="col-span-1 flex justify-center sm:col-span-2">
                            <PrimaryButton class="text-sm py-1 px-3">
                                {{ $t('form.start_payment') }}
                            </PrimaryButton>
                        </div>
                        <div class="col-span-2 flex items-center rounded-lg bg-gray-100 justify-center py-2">
                            <p class="text-gray-400 text-lg px-4">Seleccione un pie de página</p>
                            <PhotoIcon class="w-12 h-12 text-gray-400"/>
                        </div>
                    </div>



                    <div id="fields" class="bg-white px-2 py-3 rounded-2xl shadow-lg">
                        <div v-if="form.configuration && form.configuration.fields && form.configuration.fields.length > 0">
                            <h3 class="mb-4 font-semibold text-gray-900">Fields</h3>
                            <ul class="w-48 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg">
                                <li v-for="field in form.configuration.fields" :key="field.name"
                                    class="w-full border-b border-gray-200 rounded-t-lg">
                                    <div class="flex items-center ps-3">
                                        <input
                                            :id="'checkbox-' + field.name"
                                            type="checkbox"
                                            :checked="field.active === 'true'"
                                            @change="toggleFieldActive(field)"
                                            value=""
                                            class="w-4 h-4 text-orange-300 bg-gray-50 border-orange-300 rounded focus:ring-orange-500 focus:ring-2"
                                        >
                                        <label
                                            :for="'checkbox-' + field.name"
                                            class="w-full py-3 ms-2 text-sm font-medium text-gray-900"
                                        >
                                            {{ field.name }}
                                        </label>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div v-else>
                            <p class="text-gray-500 text-center">No fields available.</p>
                        </div>
                        <div class="mt-4">
                            <PrimaryButton @click="submit">
                                Save Changes
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </main>



        </template>
    </AuthenticatedLayout>
</template>

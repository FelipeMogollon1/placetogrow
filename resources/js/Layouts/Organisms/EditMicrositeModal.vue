<script setup>
import { ref, reactive, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputForm from "@/Layouts/Atoms/InputForm.vue";
import SelectForm from "@/Layouts/Atoms/SelectForm.vue";
import InputFileForm from "@/Layouts/Atoms/InputFileForm.vue";
import {routes} from "@/routes.js";
import axios from "axios";


const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['close', 'update']);

const form = reactive({
    nombre: '',
    // Añade aquí más campos según sea necesario
});

watch(() => props.item, () => {
    if (props.item) {
        form.nombre = props.item.nombre;
        // Copia aquí más campos según sea necesario
    }
}, { immediate: true });

const closeModal = () => {
    emit('close');
};

const submitForm = async () => {
    try {
        const updateUrl = routes.admin.microsites.update.replace(':id', props.item.id);
        await axios.put(updateUrl, form);
        emit('update');
        closeModal();
    } catch (error) {
        console.error('Error updating microsite:', error);
    }
};

const categories = ref([]);
const refreshCategories = async () => {
    try {
        const CategoryIndexUrl = routes.admin.categories.index;
        const response = await axios.get(CategoryIndexUrl);
        categories.value = response.data;
        console.log(categories)
    } catch (error) {
        console.error('Error fetching microsites:', error);
    }
};

const getCategories = async () => {
    await refreshCategories();
};

getCategories();

</script>

<template>
    <Modal :show="show" maxWidth="2xl" @close="closeModal">
        <div class="p-6">
            <h1 class="text-lg font-medium p-5 text-gray-900">
                Editar Micrositio
            </h1>
            <form @submit.prevent="submitForm">
                <div class="mt-4">
                    <InputForm
                        type="text"
                        name="Nombre del micrositio"
                        id="name"
                        label="Nombre del micrositio"
                        v-model="form.name"
                        required
                    />
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">

                    <InputForm
                        type="text"
                        name="payment_expiration_time"
                        id="payment_expiration_time"
                        label="Tiempo de expiración (Segundos)"
                        v-model="form.payment_expiration_time"
                        required
                    />

                    <SelectForm
                        name="category_id"
                        id="category_id"
                        label="Categoria"
                        :options="[
                                        { value: 'option1', text: 'Option 1' },
                                        { value: 'option2', text: 'Option 2' },
                                        { value: 'option3', text: 'Option 3' }
                                      ]"
                        v-model="form.category_id"
                        required
                    />
                </div>

                <div class="grid md:grid-cols-2 md:gap-6">
                    <SelectForm
                        name="currency"
                        id="currency"
                        label="Moneda"
                        :options="[
                                        { value: 'option1', text: 'Option 1' },
                                        { value: 'option2', text: 'Option 2' },
                                        { value: 'option3', text: 'Option 3' }
                                      ]"
                        v-model="form.currency"
                        required
                    />

                    <SelectForm
                        name="microsite_type"
                        id="microsite_type"
                        label="Tipo de Micrositio"
                        :options="[
                                        { value: 'option1', text: 'Option 1' },
                                        { value: 'option2', text: 'Option 2' },
                                        { value: 'option3', text: 'Option 3' }
                                      ]"
                        v-model="form.microsite_type"
                        required
                    />

                </div>
                <div class="grid md:grid-cols-2 md:gap-6">
                    <InputForm
                        type="text"
                        name="document_type"
                        id="document_type"
                        label="Tipo de documento"
                        v-model="form.document_type"
                        required
                    />
                    <InputForm
                        type="text"
                        name="document"
                        id="document"
                        label="Documento"
                        v-model="form.document"
                        required
                    />

                </div>
<!--
                <div class="mt-4">
                    <InputFileForm
                        name="Logo"
                        id="file_input"
                        label="Logo"
                        v-model="form.logo"
                    />
                </div>
---->
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="closeModal" class="mr-2 bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>



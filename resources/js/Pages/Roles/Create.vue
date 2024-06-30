<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { defineProps, ref } from "vue";

const selectedRoles = ref({});

function toggleGroup(group, selectedGroups, permissions) {
    const isChecked = !selectedGroups[group];
    selectedGroups[group] = isChecked;

    permissions[group].forEach(permission => {
        document.getElementById(`${group}.${permission}`).checked = isChecked;
    });
}

function processPermissions(permissions) {
    let bundledPermissions = {};

    permissions.forEach(permission => {
        let permissionName = permission.name;
        let token = permissionName.split('.');

        if (!bundledPermissions[token[0]]) {
            bundledPermissions[token[0]] = [];
        }
        bundledPermissions[token[0]].push(token[1]);
    });

    return bundledPermissions;
}

const initialValues = {
    name: "",
    permission: ""
}

const form = useForm(initialValues)

const submit = () => {
    form.post(route('roles.store'))
}

const props = defineProps({
    permissions: {
        type: Array
    }
})

const groupedPermissions = processPermissions(props.permissions);

</script>

<template>
    <Head title="Crear Rol" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear Rol</h2>
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
                        <form class="w-1/3 py-8 space-y-5" @submit.prevent="submit" id="formPermissions">
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Administrador general"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div v-for="(permissions, group) in groupedPermissions" :key="group">
                                <div class="flex items-center">
                                    <input
                                        type="checkbox"
                                        :id="`group_${group}`"
                                        v-model="selectedRoles[group]"
                                        @click="toggleGroup(group, selectedRoles, groupedPermissions)"
                                    />
                                    <InputLabel :for="`group_${group}`" :value="group" class="ml-2" />
                                </div>
                                <div class="ml-6">
                                    <div v-for="permission in permissions" :key="permission" class="flex items-center mt-2">
                                        <input
                                            type="checkbox"
                                            :id="`${group}.${permission}`"
                                            :value="`${group}.${permission}`"
                                            name="permissions[]"
                                            class="form-checkbox"
                                        />
                                        <InputLabel :for="`${group}.${permission}`" :value="permission" class="ml-2" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <PrimaryButton>
                                    Crear Rol
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

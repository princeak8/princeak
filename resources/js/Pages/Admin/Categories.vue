<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    categories: Array,
    messagesCount: Number
});

console.log("categories:", props.categories);

const showModal = ref(false);
const modalTitle = ref('Add Category');
const form = ref({
    id: null,
    name: '',
    description: ''
});
const errors = ref({});

const openAddModal = () => {
    resetForm();
    modalTitle.value = 'Add Category';
    showModal.value = true;
};

const openEditModal = (category) => {
    form.value = {
        id: category.id,
        name: category.name,
        description: category.description
    };
    modalTitle.value = 'Edit Category';
    showModal.value = true;
};

const resetForm = () => {
    form.value = {
        id: null,
        name: '',
        description: ''
    };
    errors.value = {};
};

const submitForm = () => {
    if (form.value.id) {
        // Update existing category
        router.put(route('admin.categories.update', form.value.id), form.value, {
            onSuccess: () => {
                showModal.value = false;
                resetForm();
            },
            onError: (err) => {
                errors.value = err;
            },
        });
    } else {
        // Create new category
        router.post(route('admin.categories.save'), form.value, {
            onSuccess: () => {
                showModal.value = false;
                resetForm();
            },
            onError: (err) => {
                errors.value = err;
            },
        });
    }
};

const deleteCategory = (id) => {
    if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
        router.delete(route('admin.categories.delete', id));
    }
};
</script>

<template>
    <Head title="Categories" />

    <AuthenticatedLayout :messagesCount="messagesCount">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Categories
                </h2>
                <button
                    @click="openAddModal"
                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                >
                    Add Category
                </button>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Categories Table -->
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            ID
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Name
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr v-for="category in categories" :key="category.id">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ category.id }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ category.name }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button
                                                @click="openEditModal(category)"
                                                class="mr-3 text-blue-600 hover:text-blue-900"
                                            >
                                                Edit
                                            </button>
                                            <button v-if="category.canDelete"
                                                @click="deleteCategory(category.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-if="categories.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
                            <div class="mt-6">
                                <button
                                    @click="openAddModal"
                                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    Add Category
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add/Edit Category Modal -->
        <Modal :show="showModal" @close="showModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ modalTitle }}
                </h2>

                <div class="mt-6">
                    <InputLabel for="name" value="Name" class="sr-only" />

                    <TextInput id="name" ref="nameInput" v-model="form.name" type="text" class="mt-1 block w-full" placeholder="Category Name"/>

                    <p v-if="errors.name" class="mt-2 text-sm text-red-600">
                        {{ errors.name }}
                    </p>
                </div>

                <div class="mt-6">
                    <InputLabel for="description" value="Description" class="sr-only" />

                    <TextInput id="description" ref="descriptionInput" v-model="form.description" type="text" class="mt-1 block w-full" placeholder="Description"/>

                    <p v-if="errors.description" class="mt-2 text-sm text-red-600">
                        {{ errors.description }}
                    </p>
                </div>

                <div class="mt-6 flex justify-end space-x-4">
                    <SecondaryButton @click="showModal = false">
                        Cancel
                    </SecondaryButton>

                    <PrimaryButton
                        @click="submitForm"
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ form.id ? 'Update' : 'Create' }}
                    </PrimaryButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
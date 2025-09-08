<template>
    <!-- Add Tag Modal -->
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                @click="close"
            ></div>

            <!-- Modal panel -->
            <div class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                Add New Tag
                            </h3>
                            <div class="mt-4">
                                <label for="new-tag-name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tag Name
                                </label>
                                <input 
                                    id="new-tag-name" 
                                    v-model="tagName" 
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                    placeholder="Enter tag name" 
                                    @keyup.enter="submit"
                                    maxlength="50"
                                />
                                <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button
                        type="button"
                        @click="submit"
                        :disabled="!tagName.trim() || loading"
                        class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed sm:ml-3 sm:w-auto"
                    >
                        <svg v-if="loading" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ loading ? 'Adding...' : 'Add Tag' }}
                    </button>
                    <button
                        type="button"
                        @click="close"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['tag-added', 'close']);

const props = defineProps({
    show: Boolean,
    tags: Array
});

const tagName = ref('');
const loading = ref(false);
const error = ref('');

const close = () => {
    tagName.value = '';
    error.value = '';
    emit('close');
};

const submit = async () => {
    if (!tagName.value.trim()) return;
    
    loading.value = true;
    error.value = '';
    
    try {
        const response = await axios.post(route('admin.tags.save'), { 
            name: tagName.value.trim()
        });
        const { data:newTag } = response.data;
        
        console.log('new tag added');
        emit('tag-added', newTag);
        console.log('close modal');
        close();
    } catch (err) {
        error.value = "An error occurred while trying to create a new Tag";
        console.error('Error creating tag:', err);
    } finally {
        loading.value = false;
    }
};
</script>
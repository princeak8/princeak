<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    categories: Array,
    tags: Array,
});

// Form data
const form = useForm({
    title: '',
    category_id: '',
    tag_ids: [],
    content: '',
    preview: '',
    cover_photo: null,
    status: 'draft'
});

// File handling
const coverPhotoPreview = ref(null);
const fileInput = ref(null);

// Tag selection
const selectedTags = ref([]);
const showAddTagModal = ref(false);
const newTagName = ref('');
const addingTag = ref(false);

// Add new tag
const addNewTag = async () => {
    if (!newTagName.value.trim()) return;
    
    addingTag.value = true;
    
    try {
        // Make a request to create the new tag
        const response = await fetch(route('admin.tags.store'), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                name: newTagName.value.trim()
            })
        });
        
        if (response.ok) {
            const newTag = await response.json();
            
            // Add the new tag to the tags array
            props.tags.push(newTag);
            
            // Select the new tag
            form.tag_ids.push(newTag.id);
            
            // Reset and close modal
            newTagName.value = '';
            showAddTagModal.value = false;
        } else {
            console.error('Failed to create tag');
        }
    } catch (error) {
        console.error('Error creating tag:', error);
    } finally {
        addingTag.value = false;
    }
};

// Close modal
const closeAddTagModal = () => {
    showAddTagModal.value = false;
    newTagName.value = '';
};

// Handle file upload
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.cover_photo = file;
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            coverPhotoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Remove cover photo
const removeCoverPhoto = () => {
    form.cover_photo = null;
    coverPhotoPreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

// Handle tag selection
const toggleTag = (tagId) => {
    const index = form.tag_ids.indexOf(tagId);
    if (index > -1) {
        form.tag_ids.splice(index, 1);
    } else {
        form.tag_ids.push(tagId);
    }
};

// Check if tag is selected
const isTagSelected = (tagId) => {
    return form.tag_ids.includes(tagId);
};

// Get selected tag names for display
const selectedTagNames = computed(() => {
    return props.tags
        .filter(tag => form.tag_ids.includes(tag.id))
        .map(tag => tag.name);
});

// Submit form
const submit = () => {
    form.post(route('admin.posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Reset form or redirect
        },
        onError: (errors) => {
            console.log('Validation errors:', errors);
        }
    });
};

// Save as draft
const saveAsDraft = () => {
    form.status = 'draft';
    submit();
};

// Publish post
const publishPost = () => {
    form.status = 'published';
    submit();
};
</script>

<template>
    <Head title="Create New Post" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Create New Post
                </h2>
                <Link
                    :href="route('admin.posts.index')"
                    class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                >
                    Back to Posts
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Title -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Title *
                                </label>
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.title }"
                                    placeholder="Enter post title"
                                    required
                                />
                                <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.title }}
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category *
                                </label>
                                <select
                                    id="category_id"
                                    v-model="form.category_id"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.category_id }"
                                    required
                                >
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.category_id }}
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tags
                                    </label>
                                    <button
                                        type="button"
                                        @click="showAddTagModal = true"
                                        class="inline-flex items-center rounded-md bg-green-600 px-3 py-1 text-sm font-medium text-white hover:bg-green-700"
                                    >
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add New Tag
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-2 mb-3" v-if="selectedTagNames.length > 0">
                                    <span
                                        v-for="tagName in selectedTagNames"
                                        :key="tagName"
                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800"
                                    >
                                        {{ tagName }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                    <label
                                        v-for="tag in tags"
                                        :key="tag.id"
                                        class="inline-flex items-center cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="tag.id"
                                            :checked="isTagSelected(tag.id)"
                                            @change="toggleTag(tag.id)"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">{{ tag.name }}</span>
                                    </label>
                                </div>
                                <div v-if="form.errors.tag_ids" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tag_ids }}
                                </div>
                            </div>

                            <!-- Cover Photo -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cover Photo
                                </label>
                                <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                    <div class="space-y-1 text-center" v-if="!coverPhotoPreview">
                                        <svg
                                            class="mx-auto h-12 w-12 text-gray-400"
                                            stroke="currentColor"
                                            fill="none"
                                            viewBox="0 0 48 48"
                                        >
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label
                                                for="cover_photo"
                                                class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500"
                                            >
                                                <span>Upload a file</span>
                                                <input
                                                    id="cover_photo"
                                                    ref="fileInput"
                                                    name="cover_photo"
                                                    type="file"
                                                    class="sr-only"
                                                    accept="image/*"
                                                    @change="handleFileUpload"
                                                />
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                    <div v-else class="relative">
                                        <img
                                            :src="coverPhotoPreview"
                                            alt="Cover photo preview"
                                            class="max-h-64 rounded-lg"
                                        />
                                        <button
                                            type="button"
                                            @click="removeCoverPhoto"
                                            class="absolute -top-2 -right-2 inline-flex items-center rounded-full bg-red-600 p-1 text-white shadow-sm hover:bg-red-700"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div v-if="form.errors.cover_photo" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.cover_photo }}
                                </div>
                            </div>

                            <!-- Preview -->
                            <div class="mb-6">
                                <label for="preview" class="block text-sm font-medium text-gray-700 mb-2">
                                    Preview/Excerpt
                                </label>
                                <textarea
                                    id="preview"
                                    v-model="form.preview"
                                    rows="3"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.preview }"
                                    placeholder="Enter a brief preview or excerpt of the post"
                                ></textarea>
                                <div v-if="form.errors.preview" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.preview }}
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="mb-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Content *
                                </label>
                                <!-- Basic textarea - you can replace this with a rich text editor like TinyMCE, CKEditor, or Quill -->
                                <textarea
                                    id="content"
                                    v-model="form.content"
                                    rows="15"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.content }"
                                    placeholder="Write your post content here..."
                                    required
                                ></textarea>
                                <div v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.content }}
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    You can use HTML tags for formatting.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between space-x-4">
                        <div class="flex space-x-3">
                            <button
                                type="button"
                                @click="saveAsDraft"
                                :disabled="form.processing"
                                class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                                <svg v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Save as Draft
                            </button>
                            
                            <button
                                type="button"
                                @click="publishPost"
                                :disabled="form.processing"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                                <svg v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Publish Post
                            </button>
                        </div>

                        <Link
                            :href="route('admin.posts.index')"
                            class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>

                <!-- Add Tag Modal -->
                <div
                    v-if="showAddTagModal"
                    class="fixed inset-0 z-50 overflow-y-auto"
                    aria-labelledby="modal-title"
                    role="dialog"
                    aria-modal="true"
                >
                    <div class="flex min-h-screen items-end justify-center px-4 pb-20 pt-4 text-center sm:block sm:p-0">
                        <!-- Background overlay -->
                        <div
                            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                            @click="closeAddTagModal"
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
                                                v-model="newTagName"
                                                type="text"
                                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                                placeholder="Enter tag name"
                                                @keyup.enter="addNewTag"
                                                maxlength="50"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                                <button
                                    type="button"
                                    @click="addNewTag"
                                    :disabled="!newTagName.trim() || addingTag"
                                    class="inline-flex w-full justify-center rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed sm:ml-3 sm:w-auto"
                                >
                                    <svg v-if="addingTag" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    {{ addingTag ? 'Adding...' : 'Add Tag' }}
                                </button>
                                <button
                                    type="button"
                                    @click="closeAddTagModal"
                                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
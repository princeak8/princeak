<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import axios from "axios";
import 'jodit/es2018/jodit.min.css';
import { JoditEditor } from 'jodit-vue';
import { Status } from '@/Enums/Post';
import AddTagModal from '@/Components/Modals/AddTag.vue';
import { Error } from '@/Composables/Error';

const { error, setError, clearError } = Error(5000);

const props = defineProps({
    post: Object,
    categories: Array,
    tags: Array,
    messagesCount: Number
});

console.log("post", props.post);

// Initialize form with post data
const form = useForm({
    title: props.post.title,
    categoryId: props.post.category?.id || '',
    tagIds: props.post.tags.map(tag => tag.id),
    content: props.post.content,
    preview: props.post.preview,
    coverPhoto: null, // Will handle existing photo separately
    coverPhotoRemoved: false,
    status: props.post.status
});

const editorConfig = ref({
    readonly: false,
    height: 500,
    toolbar: true,
    spellcheck: true,
    language: 'en',
    toolbarAdaptive: false,
    placeholder: 'Start writing your post here...'
});

// File handling
const coverPhotoPreview = ref(props.post.coverPhoto?.url || null);
const fileInput = ref(null);


// Watch for form errors reactively
watch(() => form.errors.error, (newError) => {
    if (newError) {
        setError(newError);
    }
}, { immediate: true });

// Tag selection
const showAddTagModal = ref(false);

// Handle new tag added
const handleTagAdded = (newTag) => {
    props.tags.push(newTag);
    form.tagIds.push(newTag.id);
};

// Handle file upload
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.coverPhoto = file;
        
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
    form.coverPhoto = null;
    coverPhotoPreview.value = null;
    if (fileInput.value) {
        fileInput.value.value = '';
    }
};

// Handle tag selection
const toggleTag = (tagId) => {
    const index = form.tagIds.indexOf(tagId);
    if (index > -1) {
        form.tagIds.splice(index, 1);
    } else {
        form.tagIds.push(tagId);
    }
};

// Check if tag is selected
const isTagSelected = (tagId) => {
    return form.tagIds.includes(tagId);
};

// Get selected tag names for display
const selectedTagNames = computed(() => {
    return props.tags
        .filter(tag => form.tagIds.includes(tag.id))
        .map(tag => tag.name);
});

// Submit form
const submit = () => {
    console.log("update");
    form.post(route('admin.posts.update', props.post.id), {
        preserveScroll: true,
        onSuccess: () => {
            // Clear error on success
            clearError();
        },
        onError: (errors) => {
            console.log('Validation errors:', errors);
        }
    });
};

</script>

<template>
    <Head title="Update Post" />

    <AuthenticatedLayout :messagesCount="messagesCount">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Edit Post
                </h2>
                <Link
                    :href="route('admin.posts')"
                    class="inline-flex items-center rounded-md bg-gray-600 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                >
                    Back to Posts
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <p v-if="error != ''" class="text-red-700 w-full bg-red-100">{{ error }}</p>
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Title -->
                            <div class="mb-6">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Title *
                                </label>
                                <input
                                    id="title" v-model="form.title" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.title }" placeholder="Enter post title" required
                                />
                                <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.title }}
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="mb-6">
                                <label for="categoryId" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category *
                                </label>
                                <select
                                    id="categoryId" v-model="form.categoryId"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    :class="{ 'border-red-500': form.errors.categoryId }" required
                                >
                                    <option value="">Select a category</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.categoryId" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.categoryId }}
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Tags
                                    </label>
                                    <button
                                        type="button" @click="showAddTagModal = true"
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
                                        v-for="tagName in selectedTagNames" :key="tagName"
                                        class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-medium text-blue-800"
                                    >
                                        {{ tagName }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-10">
                                    <label
                                        v-for="tag in tags" :key="tag.id" class="inline-flex items-center cursor-pointer">
                                        <input
                                            type="checkbox" :value="tag.id" :checked="isTagSelected(tag.id)" @change="toggleTag(tag.id)"
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">{{ tag.name }}</span>
                                    </label>
                                </div>
                                <div v-if="form.errors.tagIds" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tagIds }}
                                </div>
                            </div>

                            <!-- Cover Photo Section -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cover Photo
                                </label>
                                <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
                                    <div v-if="!coverPhotoPreview" class="space-y-1 text-center">
                                        <!-- Upload new photo UI -->
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            />
                                        </svg>
                                        <div class="flex text-sm text-gray-600">
                                            <label
                                                for="coverPhoto"
                                                class="relative cursor-pointer rounded-md bg-white font-medium text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 hover:text-blue-500"
                                            >
                                                <span>Upload a file</span>
                                                <input id="coverPhoto" ref="fileInput" name="coverPhoto" type="file" class="sr-only" accept="image/*"
                                                    @change="handleFileUpload"
                                                />
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                                    </div>
                                    <div v-else class="relative">
                                        <img :src="coverPhotoPreview" alt="Cover photo preview" class="max-h-64 rounded-lg" />
                                        <button type="button" @click="removeCoverPhoto"
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
                                <textarea id="preview" v-model="form.preview" rows="3"
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
                                <div class="mt-1">
                                    <JoditEditor
                                        v-model="form.content"
                                        :config="editorConfig"
                                    />
                                </div>
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
                            
                            <button type="button" @click="submit" :disabled="form.processing"
                                class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                            >
                                <svg v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Update Post
                            </button>
                        </div>

                        <Link
                            :href="route('admin.posts')"
                            class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>

                

                <AddTagModal :show="showAddTagModal" :tags="tags" @close="showAddTagModal = false" @tag-added="handleTagAdded" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
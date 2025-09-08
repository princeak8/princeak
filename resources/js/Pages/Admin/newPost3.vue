<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import 'jodit/es2018/jodit.min.css';
import { JoditEditor } from 'jodit-vue'; // Note the named import

const props = defineProps({
    categories: Array,
    tags: Array,
});

const form = useForm({
    title: '',
    category_id: '',
    tags: [],
    content: '<p>Start writing here...</p>',
    preview: '',
    cover_photo: null,
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

const submit = () => {
    form.post(route('admin.posts.store'), {
        onSuccess: () => form.reset(),
    });
};

const fileInput = ref(null);

const handleFileChange = (e) => {
    form.cover_photo = e.target.files[0];
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
                    class="rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300"
                >
                    Back to Posts
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">
                                    Title
                                </label>
                                <input
                                    id="title"
                                    v-model="form.title"
                                    type="text"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                />
                                <p v-if="form.errors.title" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.title }}
                                </p>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">
                                    Category
                                </label>
                                <select
                                    id="category"
                                    v-model="form.category_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required
                                >
                                    <option value="" disabled>Select a category</option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.category_id }}
                                </p>
                            </div>

                            <!-- Tags -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Tags
                                </label>
                                <div class="mt-2 space-y-2">
                                    <div
                                        v-for="tag in tags"
                                        :key="tag.id"
                                        class="flex items-center"
                                    >
                                        <input
                                            :id="`tag-${tag.id}`"
                                            v-model="form.tags"
                                            type="checkbox"
                                            :value="tag.id"
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        />
                                        <label
                                            :for="`tag-${tag.id}`"
                                            class="ml-2 block text-sm text-gray-700"
                                        >
                                            {{ tag.name }}
                                        </label>
                                    </div>
                                </div>
                                <p v-if="form.errors.tags" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tags }}
                                </p>
                            </div>

                            <!-- Cover Photo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Cover Photo
                                </label>
                                <div class="mt-1 flex items-center">
                                    <input
                                        ref="fileInput"
                                        type="file"
                                        accept="image/*"
                                        @change="handleFileChange"
                                        class="hidden"
                                    />
                                    <button
                                        type="button"
                                        @click="fileInput.click()"
                                        class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    >
                                        Choose File
                                    </button>
                                    <span class="ml-2 text-sm text-gray-500">
                                        {{ form.cover_photo ? form.cover_photo.name : 'No file chosen' }}
                                    </span>
                                </div>
                                <p v-if="form.errors.cover_photo" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.cover_photo }}
                                </p>
                            </div>

                            <!-- Preview -->
                            <div>
                                <label for="preview" class="block text-sm font-medium text-gray-700">
                                    Preview Text
                                </label>
                                <textarea
                                    id="preview"
                                    v-model="form.preview"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                ></textarea>
                                <p v-if="form.errors.preview" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.preview }}
                                </p>
                            </div>

                            <!-- Jodit Editor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">
                                    Content
                                </label>
                                <div class="mt-1">
                                    <JoditEditor
                                        v-model="form.content"
                                        :config="editorConfig"
                                    />
                                </div>
                                <p v-if="form.errors.content" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.content }}
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    Create Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
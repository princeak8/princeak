<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Status } from '@/Enums/Post';

const props = defineProps({
    posts: Array,
    categories: Array,
    tags: Array,
    filters: Object,
    messagesCount: Number
});

const form = useForm({
    id: '',
});

const togglePublish = (id) => {
    form.id = id;

    form.post(route('admin.posts.publish_toggle'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success
        },
        onError: (errors) => {
            console.log('errors:', errors);
        }
    });
}

const activeFilters = ref({
    category: props?.filters?.category || null,
    tag: props?.filters?.tag || null,
    status: props?.filters?.status || null,
});

const filteredPosts = computed(() => {
    return props.posts.filter(post => {
        const matchesCategory = !activeFilters.value.category || 
            (post.category && post.category.id == activeFilters.value.category);
        const matchesTag = !activeFilters.value.tag || 
            post.tags.some(tag => tag.id == activeFilters.value.tag);
        const matchesStatus = !activeFilters.value.status || 
            post.status === activeFilters.value.status;
            
        return matchesCategory && matchesTag && matchesStatus;
    });
});

const applyFilters = () => {
    router.get(route('admin.posts.index'), activeFilters.value);
};

const clearFilters = () => {
    activeFilters.value = { category: null, tag: null, status: null };
    router.get(route('admin.posts.index'));
};

const deletePost = (postId) => {
    if (confirm('Are you sure you want to delete this post?')) {
        router.delete(route('admin.posts.destroy', postId));
    }
};
</script>

<template>
    <Head title="Posts" />

    <AuthenticatedLayout :messagesCount="messagesCount">
        <template #header>
            <div class="flex flex-row justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Posts
                </h2>
                <Link :href="route('admin.posts.create')"
                    class="inline-flex items-center rounded-md bg-purple-600 px-3 py-1 text-sm font-medium text-white hover:bg-purple-700 cursor-pointer"
                >
                    New Post
                </Link>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Filter Section -->
                        <div class="mb-8 space-y-4">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium">Filters</h3>
                                <button 
                                    @click="clearFilters"
                                    class="text-sm text-gray-500 hover:text-gray-700"
                                >
                                    Clear all
                                </button>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <h4 class="mb-2 text-sm font-medium text-gray-500">Categories</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-for="category in categories"
                                        :key="category.id"
                                        @click="activeFilters.category = activeFilters.category === category.id ? null : category.id; applyFilters()"
                                        :class="{
                                            'bg-blue-100 text-blue-800': activeFilters.category === category.id,
                                            'bg-gray-100 text-gray-800': activeFilters.category !== category.id,
                                        }"
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                    >
                                        {{ category.name }}
                                    </button>
                                </div>
                            </div>

                            <!-- Tag Filter -->
                            <div>
                                <h4 class="mb-2 text-sm font-medium text-gray-500">Tags</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        v-for="tag in tags"
                                        :key="tag.id"
                                        @click="activeFilters.tag = activeFilters.tag === tag.id ? null : tag.id; applyFilters()"
                                        :class="{
                                            'bg-green-100 text-green-800': activeFilters.tag === tag.id,
                                            'bg-gray-100 text-gray-800': activeFilters.tag !== tag.id,
                                        }"
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                    >
                                        {{ tag.name }}
                                    </button>
                                </div>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <h4 class="mb-2 text-sm font-medium text-gray-500">Status</h4>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        @click="activeFilters.status = activeFilters.status === 'published' ? null : 'published'; applyFilters()"
                                        :class="{
                                            'bg-green-100 text-green-800': activeFilters.status === 'published',
                                            'bg-gray-100 text-gray-800': activeFilters.status !== 'published',
                                        }"
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                    >
                                        Published
                                    </button>
                                    <button
                                        @click="activeFilters.status = activeFilters.status === 'draft' ? null : 'draft'; applyFilters()"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800': activeFilters.status === 'draft',
                                            'bg-gray-100 text-gray-800': activeFilters.status !== 'draft',
                                        }"
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                    >
                                        Draft
                                    </button>
                                    <button
                                        @click="activeFilters.status = activeFilters.status === 'archived' ? null : 'archived'; applyFilters()"
                                        :class="{
                                            'bg-red-100 text-red-800': activeFilters.status === 'archived',
                                            'bg-gray-100 text-gray-800': activeFilters.status !== 'archived',
                                        }"
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                    >
                                        Archived
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Posts Grid -->
                        <div v-if="filteredPosts.length > 0" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <div v-for="post in filteredPosts" :key="post.id" class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                                <!-- Cover Photo -->
                                <div class="h-48 overflow-hidden bg-gray-100">
                                    <img 
                                        v-if="post.coverPhoto" 
                                        :src="post.coverPhoto.url" 
                                        :alt="post.title"
                                        class="h-full w-full object-cover"
                                    >
                                    <div v-else class="flex h-full items-center justify-center bg-gray-200 text-gray-400">
                                        <svg class="h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Post Content -->
                                <div class="p-4">
                                    <!-- Status Badge -->
                                    <span
                                        :class="{
                                            'bg-green-100 text-green-800': post.status === 'published',
                                            'bg-yellow-100 text-yellow-800': post.status === 'draft',
                                            'bg-red-100 text-red-800': post.status === 'archived',
                                        }"
                                        class="mb-2 inline-block rounded-full px-2 py-1 text-xs font-medium"
                                    >
                                        {{ post.status }}
                                    </span>

                                    <!-- Title -->
                                    <h3 class="mb-2 text-lg font-semibold text-gray-900 line-clamp-1">
                                        {{ post.title }}
                                    </h3>

                                    <!-- Category & Tags -->
                                    <div class="mb-3 space-y-1">
                                        <div v-if="post.category" class="text-sm text-blue-600">
                                            {{ post.category.name }}
                                        </div>
                                        <div class="flex flex-wrap gap-1">
                                            <span 
                                                v-for="tag in post.tags" 
                                                :key="tag.id"
                                                class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600"
                                            >
                                                {{ tag.name }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <p class="mb-4 text-sm text-gray-600 line-clamp-2">
                                        {{ post.preview }}
                                    </p>

                                    <!-- Actions -->
                                    <div class="flex items-center justify-between">
                                        <div class="text-xs text-gray-500">
                                            {{ post.views || 0 }} views
                                        </div>
                                        <div class="space-x-2">
                                            <Link 
                                                :href="route('admin.posts.show', post.id)"
                                                class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 hover:bg-gray-200"
                                            >
                                                View
                                            </Link>
                                            <Link 
                                                :href="route('admin.posts.edit', post.id)"
                                                class="rounded bg-blue-100 px-2 py-1 text-xs text-blue-700 hover:bg-blue-200"
                                            >
                                                Edit
                                            </Link>
                                            <button 
                                                v-if="post.status == Status.PUBLISHED || post.status == Status.DRAFT"
                                                @click="togglePublish(post.id)"
                                                class="rounded px-2 py-1 text-xs hover:bg-green-200"
                                                :class="[post.status === Status.PUBLISHED ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700']"
                                            >
                                                {{ post.status === Status.DRAFT ? 'Publish' : 'Unpublish' }}
                                            </button>
                                            <button 
                                                @click="deletePost(post.id)"
                                                class="rounded bg-red-100 px-2 py-1 text-xs text-red-700 hover:bg-red-200"
                                            >
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="flex flex-col items-center justify-center py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No posts found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or create a new post.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('admin.posts.create')"
                                    class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600"
                                >
                                    New Post
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
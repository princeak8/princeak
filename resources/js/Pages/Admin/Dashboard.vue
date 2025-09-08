<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    postsCount: Number,
    categoriesCount: Number,
    recentPosts: Array,
    messagesCount: Number
});
console.log("recentPosts", props.recentPosts);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout :messagesCount="messagesCount">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <!-- Posts Count -->
                            <div class="rounded-lg bg-blue-50 p-6 shadow">
                                <div class="flex items-center">
                                    <div class="p-3">
                                        <svg
                                            class="h-8 w-8 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">
                                            Total Posts
                                        </p>
                                        <p class="text-2xl font-semibold text-gray-900">
                                            {{ postsCount }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Categories Count -->
                            <div class="rounded-lg bg-green-50 p-6 shadow">
                                <div class="flex items-center">
                                    <div class="p-3">
                                        <svg
                                            class="h-8 w-8 text-green-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">
                                            Total Categories
                                        </p>
                                        <p class="text-2xl font-semibold text-gray-900">
                                            {{ categoriesCount }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="rounded-lg bg-purple-50 p-6 shadow">
                                <div class="flex items-center">
                                    <div class="p-3">
                                        <svg
                                            class="h-8 w-8 text-purple-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-500">
                                            Quick Actions
                                        </p>
                                        <div class="mt-2 space-x-2">
                                            <Link
                                                :href="route('admin.posts.create')"
                                                class="inline-flex items-center rounded-md bg-purple-600 px-3 py-1 text-sm font-medium text-white hover:bg-purple-700 cursor-pointer"
                                            >
                                                New Post
                                            </Link>
                                            <Link
                                                :href="route('admin.categories')"
                                                class="inline-flex items-center rounded-md bg-purple-600 px-3 py-1 text-sm font-medium text-white hover:bg-purple-700"
                                            >
                                                New Category
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Posts -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900">
                                Recent Posts
                            </h3>
                            <div class="mt-4 overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th
                                                scope="col"
                                                class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                            >
                                                Title
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                            >
                                                Status
                                            </th>
                                            <th
                                                scope="col"
                                                class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                            >
                                                Published At
                                            </th>
                                            <th
                                                scope="col"
                                                class="relative py-3.5 pl-3 pr-4 sm:pr-6"
                                            >
                                                <span class="sr-only">Edit</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="post in recentPosts" :key="post.id">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                {{ post.title }}
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                <span
                                                    :class="{
                                                        'bg-green-100 text-green-800': post.status === 'published',
                                                        'bg-yellow-100 text-yellow-800': post.status === 'draft',
                                                        'bg-red-100 text-red-800': post.status === 'archived',
                                                    }"
                                                    class="inline-flex rounded-full px-2 text-xs font-semibold leading-5"
                                                >
                                                    {{ post.status }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                {{ post.published_at ? new Date(post.published_at).toLocaleDateString() : 'Not published' }}
                                            </td>
                                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <Link
                                                    :href="route('admin.posts.edit', post.id)"
                                                    class="text-blue-600 hover:text-blue-900"
                                                >
                                                    Edit
                                                </Link>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
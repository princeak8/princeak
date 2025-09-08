<template>
    <Layout :recentPosts="recentPosts" :categories="categories">
      <div class="space-y-8">
        <h2 class="font-bold text-2xl">{{ category.name }}</h2>

        <div v-if="posts.length == 0">
            <p>
                No posts found under this category
            </p>
        </div>
        <div v-for="post in posts" :key="post.id" class="bg-white rounded-lg shadow-sm overflow-hidden">
          <Link :href="`/posts/${post.slug}`" class="block cursor-pointer">
            <img :src="post.coverPhoto.url" :alt="post.title" class="w-full h-64 object-cover">
          </Link>
          <div class="p-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
              <span v-if="post.category" class="mr-4">{{ post.category.name }}</span>
              <span>{{ post.commentsCount }} comments</span>
            </div>
            <Link :href="`/posts/${post.id}`" class="block">
              <h2 class="text-xl font-bold text-gray-900 mb-2">{{ post.title }}</h2>
              <p class="text-gray-600 mb-4">{{ post.preview }}</p>
            </Link>
            <div class="flex items-center justify-between cursor-pointer">
              <Link :href="`/posts/${post.slug}`" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Read more
              </Link>
              <div class="flex space-x-2">
                <span v-for="tag in post.tags" :key="tag.id" class="text-xs bg-gray-100 text-gray-800 px-2 py-1 rounded-full">
                  {{ tag.name }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Layout>
  </template>
  
  <script setup>
  import { Link } from '@inertiajs/vue3';
  import Layout from '@/Layouts/PublicLayout.vue';
  
  const props = defineProps({
    category: Object,
    posts: Array,
    recentPosts: Array,
    categories: Array,
  });
  </script>
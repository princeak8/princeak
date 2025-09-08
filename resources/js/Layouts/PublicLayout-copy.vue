<template>
    <div class="min-h-screen flex flex-col bg-gray-50">
      <!-- Header -->
      <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between items-center py-6">
            <div classname="border-red-900">
                <Link href="/" class="text-xl font-bold text-gray-900 flex flex-col ml-0 pl-0 border-red-900">
                    <ApplicationLogo class="block h-16 ml-0 pl-0 w-auto fill-current text-gray-800"/>
                    <!-- <span>Prince AK</span> -->
                </Link>
            </div>
            <nav class="hidden md:flex space-x-8">
              <Link href="/" class="text-gray-900 hover:text-indigo-600">Home</Link>
              <Link href="/about" class="text-gray-900 hover:text-indigo-600">About</Link>
              <Link href="/contact" class="text-gray-900 hover:text-indigo-600">Contact</Link>
            </nav>
            <div class="flex items-center space-x-4">
              <button v-if="$page.props.auth.user" @click="logout" class="text-gray-900 hover:text-indigo-600">Logout</button>
              <button v-else @click="showLoginModal = true" class="text-gray-900 hover:text-indigo-600">Login</button>
            </div>
          </div>
        </div>
      </header>
  
      <!-- Main Content -->
      <main class="flex-grow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <div class="flex flex-col md:flex-row gap-8">
            <!-- Main Content Area -->
            <div class="w-full md:w-2/3">
              <slot />
            </div>
  
            <!-- Sidebar -->
            <div class="w-full md:w-1/3">
              <div class="bg-white p-6 rounded-lg shadow-sm mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Posts</h3>
                <ul class="space-y-3">
                  <li v-for="post in recentPosts" :key="post.id">
                    <Link :href="`/posts/${post.slug}`" class="text-gray-700 hover:text-indigo-600">
                      {{ post.title }}
                    </Link>
                  </li>
                </ul>
              </div>
  
              <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Categories</h3>
                <ul class="space-y-2">
                  <li v-for="category in categories" :key="category.id">
                    <Link :href="`/posts/category/${category.name}`" class="text-gray-700 hover:text-indigo-600">
                      {{ category.name }} ({{ category.postsCount }})
                    </Link>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </main>
  
      <!-- Footer -->
      <footer class="bg-white border-t border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
          <p class="text-center text-gray-500 text-sm">
            &copy; {{ new Date().getFullYear() }} My Blog. All rights reserved.
          </p>
        </div>
      </footer>
  
      <!-- Login Modal -->
      <LoginModal :show="showLoginModal" @update:show="showLoginModal = $event" />
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router, Link } from '@inertiajs/vue3';
  import ApplicationLogo from '@/Components/ApplicationLogo.vue';
  import LoginModal from '@/Components/Modals/Login.vue';
  
  const props = defineProps({
    recentPosts: Array,
    categories: Array,
  });
  
  const showLoginModal = ref(false);
  const loginForm = ref({
    email: '',
    password: '',
  });
  
  const login = () => {
    router.post('/login', loginForm.value, {
      onSuccess: () => {
        showLoginModal.value = false;
        loginForm.value = { email: '', password: '' };
      },
    });
  };
  
  const logout = () => {
    router.post('/logout');
  };
  </script>
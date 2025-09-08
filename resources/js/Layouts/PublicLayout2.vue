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
          <slot />
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
  
  const showLoginModal = ref(false);
  
  const logout = () => {
    router.post('/logout');
  };
  </script>
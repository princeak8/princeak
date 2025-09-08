<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-blue-50 via-purple-50 to-cyan-50 relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <!-- Light Grid Pattern -->
      <div class="absolute inset-0 opacity-30">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(99, 102, 241, 0.2) 1px, transparent 0); background-size: 30px 30px;"></div>
      </div>
      
      <!-- Floating Particles -->
      <div class="absolute top-10 left-10 w-3 h-3 bg-gradient-to-r from-pink-400 to-rose-300 rounded-full animate-bounce shadow-lg"></div>
      <div class="absolute top-32 right-20 w-2 h-2 bg-gradient-to-r from-blue-400 to-cyan-300 rounded-full animate-pulse shadow-lg"></div>
      <div class="absolute bottom-40 left-1/4 w-4 h-4 bg-gradient-to-r from-purple-400 to-pink-300 rounded-full opacity-80 animate-bounce delay-300 shadow-lg"></div>
      <div class="absolute top-1/2 right-1/3 w-2 h-2 bg-gradient-to-r from-emerald-400 to-cyan-300 rounded-full animate-pulse delay-700 shadow-lg"></div>
      <div class="absolute bottom-20 right-10 w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-300 rounded-full animate-bounce delay-1000 shadow-lg"></div>
      
      <!-- Soft Gradient Orbs -->
      <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-r from-blue-200/40 to-purple-200/40 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gradient-to-r from-cyan-200/40 to-pink-200/40 rounded-full blur-3xl animate-pulse delay-1000"></div>
      <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-72 h-72 bg-gradient-to-r from-emerald-200/30 to-blue-200/30 rounded-full blur-3xl animate-pulse delay-2000"></div>
    </div>

    <!-- Header -->
    <header class="relative z-10 bg-white/80 backdrop-blur-md border-b border-indigo-100 shadow-lg shadow-indigo-100/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
          <div class="relative">
            <Link href="/" class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent flex items-center space-x-3 group">
              <div class="relative">
                <ApplicationLogo class="block h-12 w-auto fill-current text-indigo-500 group-hover:text-purple-500 transition-colors duration-300 drop-shadow-lg"/>
                <div class="absolute inset-0 bg-indigo-400/20 rounded-full blur-lg group-hover:bg-purple-400/20 transition-colors duration-300"></div>
              </div>
              <span class="font-bold tracking-wide">Prince AK</span>
            </Link>
          </div>
          
          <nav class="hidden md:flex space-x-8">
            <Link href="/" class="relative text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-semibold tracking-wide group">
              <span>Home</span>
              <div class="absolute -bottom-1 left-0 w-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 group-hover:w-full transition-all duration-300 rounded-full"></div>
            </Link>
            <Link href="/about" class="relative text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-semibold tracking-wide group">
              <span>About</span>
              <div class="absolute -bottom-1 left-0 w-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 group-hover:w-full transition-all duration-300 rounded-full"></div>
            </Link>
            <Link href="/contact" class="relative text-gray-700 hover:text-indigo-600 transition-colors duration-300 font-semibold tracking-wide group">
              <span>Contact</span>
              <div class="absolute -bottom-1 left-0 w-0 h-1 bg-gradient-to-r from-indigo-500 to-purple-500 group-hover:w-full transition-all duration-300 rounded-full"></div>
            </Link>
          </nav>
          
          <div class="flex items-center space-x-4">
            <button v-if="$page.props.auth.user" @click="logout" 
                    class="px-6 py-3 bg-gradient-to-r from-rose-400 to-pink-500 text-white rounded-full font-semibold tracking-wide hover:from-rose-500 hover:to-pink-600 transform hover:scale-105 transition-all duration-300 shadow-lg shadow-pink-300/40 hover:shadow-xl">
              Logout
            </button>
            <button v-else @click="showLoginModal = true" 
                    class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-full font-semibold tracking-wide hover:from-indigo-600 hover:to-purple-700 transform hover:scale-105 transition-all duration-300 shadow-lg shadow-indigo-300/40 hover:shadow-xl">
              Login
            </button>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow relative z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">
          <!-- Main Content Area -->
          <div class="w-full lg:w-full">
            <div class="bg-white/70 backdrop-blur-sm rounded-3xl border border-indigo-100 shadow-xl shadow-indigo-100/30 overflow-hidden hover:shadow-2xl hover:shadow-indigo-200/40 transition-all duration-300">
              <div class="p-8">
                <slot />
              </div>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="w-full lg:w-1/3 space-y-6">
            <!-- Recent Posts -->
            <div class="bg-white/70 backdrop-blur-sm rounded-3xl border border-cyan-100 shadow-xl shadow-cyan-100/30 overflow-hidden group hover:border-cyan-200 hover:shadow-2xl hover:shadow-cyan-200/40 transition-all duration-300">
              <div class="p-6">
                <h3 class="text-xl font-bold bg-gradient-to-r from-cyan-600 to-blue-600 bg-clip-text text-transparent mb-6 tracking-wide flex items-center">
                  <div class="w-2 h-2 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-full mr-3 animate-pulse"></div>
                  Recent Posts
                </h3>
                <ul class="space-y-3">
                  <li v-for="post in recentPosts" :key="post.id" class="group/item">
                    <Link :href="`/posts/${post.slug}`" 
                          class="block p-4 rounded-2xl bg-gradient-to-r from-cyan-50 to-blue-50 hover:from-cyan-100 hover:to-blue-100 border border-transparent hover:border-cyan-200 transition-all duration-300 group/link shadow-sm hover:shadow-md transform hover:-translate-y-1">
                      <span class="text-gray-700 group-hover/link:text-cyan-700 font-semibold transition-colors duration-300">
                        {{ post.title }}
                      </span>
                      <div class="w-0 h-1 bg-gradient-to-r from-cyan-500 to-blue-500 group-hover/link:w-full transition-all duration-300 mt-2 rounded-full"></div>
                    </Link>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Categories -->
            <div class="bg-white/70 backdrop-blur-sm rounded-3xl border border-purple-100 shadow-xl shadow-purple-100/30 overflow-hidden group hover:border-purple-200 hover:shadow-2xl hover:shadow-purple-200/40 transition-all duration-300">
              <div class="p-6">
                <h3 class="text-xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-6 tracking-wide flex items-center">
                  <div class="w-2 h-2 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full mr-3 animate-pulse"></div>
                  Categories
                </h3>
                <ul class="space-y-3">
                  <li v-for="category in categories" :key="category.id" class="group/item">
                    <Link :href="`/posts/category/${category.name}`" 
                          class="flex justify-between items-center p-4 rounded-2xl bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 border border-transparent hover:border-purple-200 transition-all duration-300 group/link shadow-sm hover:shadow-md transform hover:-translate-y-1">
                      <span class="text-gray-700 group-hover/link:text-purple-700 font-semibold transition-colors duration-300">
                        {{ category.name }}
                      </span>
                      <span class="bg-gradient-to-r from-purple-500 to-pink-500 text-white text-xs px-3 py-1 rounded-full font-bold shadow-lg">
                        {{ category.postsCount }}
                      </span>
                    </Link>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Fun Stats Widget -->
            <div class="bg-white/70 backdrop-blur-sm rounded-3xl border border-emerald-100 shadow-xl shadow-emerald-100/30 overflow-hidden hover:shadow-2xl hover:shadow-emerald-200/40 transition-all duration-300">
              <div class="p-6">
                <h3 class="text-xl font-bold bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent mb-6 tracking-wide flex items-center">
                  <div class="w-2 h-2 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full mr-3 animate-pulse"></div>
                  Blog Stats
                </h3>
                <div class="space-y-4">
                  <div class="flex justify-between items-center p-3 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl">
                    <span class="text-gray-700 font-semibold">Views Today:</span>
                    <div class="flex items-center space-x-2">
                      <div class="w-16 h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="w-4/5 h-full bg-gradient-to-r from-emerald-500 to-teal-500 rounded-full animate-pulse"></div>
                      </div>
                      <span class="text-emerald-600 font-bold text-sm">1.2k</span>
                    </div>
                  </div>
                  <div class="flex justify-between items-center p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-2xl">
                    <span class="text-gray-700 font-semibold">Posts:</span>
                    <div class="flex items-center space-x-2">
                      <div class="w-16 h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="w-3/5 h-full bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full"></div>
                      </div>
                      <span class="text-blue-600 font-bold text-sm">42</span>
                    </div>
                  </div>
                  <div class="flex justify-between items-center p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl">
                    <span class="text-gray-700 font-semibold">Engagement:</span>
                    <span class="text-orange-600 font-bold text-sm flex items-center">
                      <div class="w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full mr-2 animate-bounce"></div>
                      High
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/70 backdrop-blur-sm rounded-3xl border border-rose-100 shadow-xl shadow-rose-100/30 overflow-hidden hover:shadow-2xl hover:shadow-rose-200/40 transition-all duration-300">
              <div class="p-6">
                <h3 class="text-xl font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent mb-6 tracking-wide flex items-center">
                  <div class="w-2 h-2 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full mr-3 animate-pulse"></div>
                  Quick Actions
                </h3>
                <div class="grid grid-cols-2 gap-3">
                  <button class="p-4 bg-gradient-to-r from-indigo-100 to-purple-100 hover:from-indigo-200 hover:to-purple-200 rounded-2xl text-indigo-700 font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    New Post
                  </button>
                  <button class="p-4 bg-gradient-to-r from-emerald-100 to-teal-100 hover:from-emerald-200 hover:to-teal-200 rounded-2xl text-emerald-700 font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    Analytics
                  </button>
                  <button class="p-4 bg-gradient-to-r from-rose-100 to-pink-100 hover:from-rose-200 hover:to-pink-200 rounded-2xl text-rose-700 font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    Settings
                  </button>
                  <button class="p-4 bg-gradient-to-r from-yellow-100 to-orange-100 hover:from-yellow-200 hover:to-orange-200 rounded-2xl text-orange-700 font-semibold text-sm transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                    Help
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 bg-white/80 backdrop-blur-md border-t border-indigo-100 shadow-lg shadow-indigo-100/50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
          <p class="text-gray-600 text-sm font-medium tracking-wide">
            &copy; {{ new Date().getFullYear() }} Prince AK. Made with ❤️ and lots of ☕
          </p>
          <div class="flex items-center space-x-6 mt-4 md:mt-0">
            <div class="flex items-center space-x-2 text-gray-500 text-xs font-semibold">
              <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse shadow-lg"></div>
              <span>All Systems Go!</span>
            </div>
            <div class="flex space-x-3">
              <div class="w-8 h-8 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform duration-300 cursor-pointer shadow-lg">T</div>
              <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform duration-300 cursor-pointer shadow-lg">G</div>
              <div class="w-8 h-8 bg-gradient-to-r from-rose-400 to-orange-400 rounded-full flex items-center justify-center text-white text-xs font-bold hover:scale-110 transition-transform duration-300 cursor-pointer shadow-lg">L</div>
            </div>
          </div>
        </div>
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

<style scoped>
/* Fun bounce animation */
@keyframes bounce-fun {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-15px) rotate(5deg); }
}

.animate-bounce-fun {
  animation: bounce-fun 2s ease-in-out infinite;
}

/* Smooth hover lift */
.hover-lift {
  transition: transform 0.3s ease;
}

.hover-lift:hover {
  transform: translateY(-2px);
}

/* Glowing effect for interactive elements */
.glow-on-hover:hover {
  box-shadow: 0 0 20px rgba(99, 102, 241, 0.3);
}
</style>
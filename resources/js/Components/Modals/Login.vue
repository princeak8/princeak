<template>
  <div v-if="show" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="flex justify-end items-start p-4">
            <button @click="close" class="text-gray-400 hover:text-gray-500">
                <span class="sr-only">Close</span>
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <!-- Tabs -->
        <div class="flex border-b border-gray-200">
            <button @click="activeTab = 'login'" 
            :class="{'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'login', 'text-gray-500 hover:text-gray-700': activeTab !== 'login'}" 
            class="py-4 px-6 text-sm font-medium flex-1 focus:outline-none"
            >
            Login
            </button>
            <button @click="activeTab = 'signup'" 
            :class="{'border-b-2 border-indigo-500 text-indigo-600': activeTab === 'signup', 'text-gray-500 hover:text-gray-700': activeTab !== 'signup'}" 
            class="py-4 px-6 text-sm font-medium flex-1 focus:outline-none"
            >
            Sign Up
            </button>
        </div>
      
        <div class="p-6">
            <p v-if="error != '' " class="text-red-800 bg-red-200 rounded py-2 text-center">{{ error }}</p>
            <!-- Login Form -->
            <form v-if="activeTab === 'login'" @submit.prevent="submitLogin">
                <div class="mb-4">
                    <label for="login-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="loginForm.email" type="email" id="login-email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-6">
                    <label for="login-password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="loginForm.password" type="password" id="login-password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Login
                    </button>
                </div>
            </form>
            
            <!-- Signup Form -->
            <form v-if="activeTab === 'signup'" @submit.prevent="submitSignup">
                <div class="mb-4">
                    <label for="signup-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input v-model="signupForm.name" type="text" id="signup-name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="signup-email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="signupForm.email" type="email" id="signup-email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-6">
                    <label for="signup-password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input v-model="signupForm.password" type="password" id="signup-password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign Up
                    </button>
                </div>
            </form>
        </div>
      
      <!-- Close Button -->
      <button @click="close" class="absolute top-4 right-4 text-gray-400 hover:text-gray-500">
        <span class="sr-only">Close</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from "axios";

const props = defineProps({
  show: Boolean,
});

const emit = defineEmits(['update:show']);

const activeTab = ref('login');

const loginForm = ref({
  email: '',
  password: '',
});

const signupForm = ref({
  name: '',
  email: '',
  password: '',
});

const error = ref('')

const close = () => {
  emit('update:show', false);
};

const submitLogin = async () => {
    await axios.post(route('login'), loginForm.value)
    .then((res) => {
        console.log("res:", res);
        loginForm.value = { email: '', password: '' };

        // Close the modal
        close();

        // Reload the current page to reflect the new authentication state
        router.reload({ only: ['auth'] });
    })
    .catch((err) => {
        console.log("error:", err);
        error.value = (err?.response?.data?.message) ? err.response.data.message : "Oops!.. An error occurred, please try again";
        setTimeout(() => error.value = "", 5000);

    })
//   router.post(route('login'), form.value, {
//     onSuccess: () => {
//       close();
//       form.value = { email: '', password: '' };
//     },
//     onError: (err) => {
//         console.log("err:", err);
//         errors.value = err;
//     },
//   });
};

const submitSignup = async () => {
    await axios.post(route('sign-up'), signupForm.value)
    .then((res) => {
        console.log("res:", res);
        signupForm.value = { name: '', email: '', password: '' };

        // Close the modal
        close();

        // Reload the current page to reflect the new authentication state
        router.reload({ only: ['auth'] });
    })
    .catch((err) => {
        console.log("error:", err);
        error.value = (err?.response?.data?.message) ? err.response.data.message : "Oops!.. An error occurred, please try again";
        setTimeout(() => error.value = "", 5000);

    })
};

// Watch for changes in the show prop and reset form when closed
watch(() => props.show, (newValue) => {
  if (!newValue) {
    if (!newValue) {
        loginForm.value = { email: '', password: '' };
        signupForm.value = { name: '', email: '', password: '' };
        activeTab.value = 'login';
        error.value = '';
    }
  }
});
</script>
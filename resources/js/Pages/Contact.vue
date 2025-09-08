<template>
    <Layout>
      <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
          <div class="p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Contact Us</h1>
            <p class="text-gray-600 mb-8">
              We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
  
            <form @submit.prevent="submitContact">
              <p v-if="contactError" class="text text-red-700 bg-red-200 py-2 text-center rounded mb-4">{{ contactError }}</p>
              <p v-if="successMessage" class="text text-green-700 bg-green-200 py-2 text-center rounded mb-4">{{ successMessage }}</p>
  
              <!-- Name field - only show if user is not logged in -->
              <div v-if="!$page.props.auth.user" class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input 
                  v-model="contactForm.name" 
                  id="name" 
                  type="text" 
                  required
                  class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Your full name"
                >
              </div>
  
              <!-- Email field - only show if user is not logged in, and it's optional -->
              <div v-if="!$page.props.auth.user" class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                  Email <span class="text-gray-400">(optional)</span>
                </label>
                <input 
                  v-model="contactForm.email" 
                  id="email" 
                  type="email" 
                  class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="your.email@example.com"
                >
              </div>
  
              <!-- Message field - always show -->
              <div class="mb-6">
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea 
                  v-model="contactForm.message" 
                  id="message" 
                  rows="5" 
                  required
                  class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  placeholder="Tell us what's on your mind..."
                ></textarea>
              </div>
  
              <button 
                type="submit" 
                :disabled="isSubmitting"
                class="w-full bg-indigo-600 text-white py-3 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {{ isSubmitting ? 'Sending...' : 'Send Message' }}
              </button>
            </form>
  
            <!-- Contact Information -->
            <div class="mt-12 pt-8 border-t border-gray-200">
              <h2 class="text-xl font-bold text-gray-900 mb-4">Other Ways to Reach Us</h2>
              <div class="space-y-3 text-gray-600">
                <div class="flex items-center">
                  <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                  </svg>
                  <span>contact@princeak.com</span>
                </div>
                <div class="flex items-center">
                  <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                  <span>+234 (703) 977-5298</span>
                </div>
                <div class="flex items-center">
                  <svg class="h-5 w-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  </svg>
                  <span>Earth, The Solar System, Milky Way, Universe</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Login Modal -->
      <LoginModal :show="showLoginModal" @update:show="showLoginModal = $event" />
    </Layout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { router, usePage } from '@inertiajs/vue3';
  import Layout from '@/Layouts/PublicLayout2.vue';
  import LoginModal from '@/Components/Modals/Login.vue';
  
  const props = defineProps({
    // Props can be added here if needed in the future
  });
  const page = usePage();

  const showLoginModal = ref(false);
  const isSubmitting = ref(false);
  const contactError = ref(null);
  const successMessage = ref(null);
  
  const contactForm = ref({
    name: '',
    email: '',
    message: ''
  });
  
  const submitContact = () => {
    // Clear previous messages
    contactError.value = null;
    successMessage.value = null;
    isSubmitting.value = true;
  
    // Prepare form data based on authentication status
    const formData = {
      message: contactForm.value.message
    };
  
    // Only include name and email if user is not logged in
    if (!page.props.auth.user) {
      formData.name = contactForm.value.name;
      formData.email = contactForm.value.email;
    }
  
    router.post(route('save_contact_message'), formData, {
      onSuccess: () => {
        successMessage.value = 'Thank you for your message! We\'ll get back to you soon.';
        contactForm.value = {
          name: '',
          email: '',
          message: ''
        };
        isSubmitting.value = false;
      },
      onError: (error) => {
        console.log("Error:", error);
        contactError.value = error.message || 'There was an error sending your message. Please try again.';
        isSubmitting.value = false;
        
        // Clear error after 5 seconds
        setTimeout(() => contactError.value = null, 5000);
      },
      onFinish: () => {
        isSubmitting.value = false;
      }
    });
  };
  </script>
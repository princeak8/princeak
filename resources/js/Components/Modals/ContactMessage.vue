<script setup>
import { computed } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: Boolean,
    message: Object,
});

const emit = defineEmits(['close']);

const formattedDate = computed(() => {
    if (!props.message?.createdAt) return '';
    return new Date(props.message.createdAt).toLocaleString();
});

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6" v-if="message">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">
                    Contact Message
                </h2>
                <button
                    @click="closeModal"
                    class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Message Details -->
            <div class="space-y-6">
                <!-- Sender Information -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <p class="text-sm text-gray-900">{{ message.name || 'Anonymous' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <p class="text-sm text-gray-900">
                                <a 
                                    v-if="message.email" 
                                    :href="`mailto:${message.email}`"
                                    class="text-indigo-600 hover:text-indigo-800 underline"
                                >
                                    {{ message.email }}
                                </a>
                                <span v-else class="text-gray-500 italic">No email provided</span>
                            </p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date Received</label>
                            <p class="text-sm text-gray-900">{{ formattedDate }}</p>
                        </div>
                    </div>
                </div>

                <!-- Message Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <div class="bg-white border border-gray-200 rounded-lg p-4 min-h-[200px]">
                        <p class="text-gray-900 whitespace-pre-wrap leading-relaxed">{{ message.message }}</p>
                    </div>
                </div>

                <!-- Status -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-2">Status:</span>
                        <span 
                            :class="{
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800': message.read,
                                'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800': !message.read
                            }"
                        >
                            {{ message.read ? 'Read' : 'Unread' }}
                        </span>
                    </div>
                    
                    <!-- Reply Button -->
                    <a 
                        v-if="message.email"
                        :href="`mailto:${message.email}?subject=Re: Contact from ${message.name || 'website'}`"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    >
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                        Reply via Email
                    </a>
                </div>
            </div>

            <!-- Close Button -->
            <div class="mt-8 flex justify-end">
                <button
                    @click="closeModal"
                    type="button"
                    class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                >
                    Close
                </button>
            </div>
        </div>
    </Modal>
</template>
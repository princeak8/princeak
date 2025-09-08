<script setup>
import AuthenticatedLayout from '@/Layouts/AdminAuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ContactMessageModal from '@/Components/Modals/ContactMessage.vue';
import axios from 'axios';

const props = defineProps({
    messages: Object, // Paginated messages object
    messagesCount: Number
});

const showMessageModal = ref(false);
const selectedMessage = ref(null);

const openMessageModal = async (message) => {
    console.log("open");
    selectedMessage.value = message;
    showMessageModal.value = true;
    
    // Mark message as read in the background if it's unread
    if (!message.read) {
        try {
            await axios.post(route('admin.read_contact_message', message.id));
            // Update the local message state
            message.read = true;
        } catch (error) {
            console.error('Error marking message as read:', error);
        }
    }
};

const closeModal = () => {
    showMessageModal.value = false;
    selectedMessage.value = null;
};

const deleteMessage = (id) => {
    if (confirm('Are you sure you want to delete this message? This action cannot be undone.')) {
        router.delete(route('admin.contact-messages.delete', id));
    }
};
</script>

<template>
    <Head title="Contact Messages" />

    <AuthenticatedLayout :messagesCount="messagesCount">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Contact Messages
                </h2>
                <div class="text-sm text-gray-600">
                    Total: {{ messages.total }} messages
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Messages Table -->
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                            Name
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Email
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Status
                                        </th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                            Date
                                        </th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white">
                                    <tr 
                                        v-for="message in messages.data" 
                                        :key="message.id"
                                        :class="{
                                            'bg-blue-50 font-medium': !message.read,
                                            'bg-white': message.read
                                        }"
                                    >
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm text-gray-900 sm:pl-6">
                                            {{ message.name || 'Anonymous' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ message.email || 'No email provided' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                                            <span 
                                                :class="{
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800': !message.read,
                                                    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800': message.read
                                                }"
                                            >
                                                {{ message.read ? 'Read' : 'Unread' }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                            {{ new Date(message.createdAt).toLocaleDateString() }}
                                        </td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <button
                                                @click="openMessageModal(message)"
                                                class="mr-3 text-indigo-600 hover:text-indigo-900"
                                            >
                                                Read Message
                                            </button>
                                            <button
                                                @click="deleteMessage(message.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-if="messages.data.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No messages found</h3>
                            <p class="mt-1 text-sm text-gray-500">No contact messages have been received yet.</p>
                        </div>

                        <!-- Pagination -->
                        <div v-if="messages.data.length > 0 && messages.last_page > 1" class="mt-6 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
                            <div class="flex flex-1 justify-between sm:hidden">
                                <component 
                                    :is="messages.prev_page_url ? 'Link' : 'span'"
                                    :href="messages.prev_page_url"
                                    :class="{
                                        'relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50': messages.prev_page_url,
                                        'relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed': !messages.prev_page_url
                                    }"
                                >
                                    Previous
                                </component>
                                <component 
                                    :is="messages.next_page_url ? 'Link' : 'span'"
                                    :href="messages.next_page_url"
                                    :class="{
                                        'relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50': messages.next_page_url,
                                        'relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-400 cursor-not-allowed': !messages.next_page_url
                                    }"
                                >
                                    Next
                                </component>
                            </div>
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium">{{ messages.from }}</span>
                                        to
                                        <span class="font-medium">{{ messages.to }}</span>
                                        of
                                        <span class="font-medium">{{ messages.total }}</span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                        <!-- Previous button -->
                                        <component 
                                            :is="messages.prev_page_url ? 'Link' : 'span'"
                                            :href="messages.prev_page_url"
                                            :class="{
                                                'relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0': messages.prev_page_url,
                                                'relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed': !messages.prev_page_url
                                            }"
                                        >
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </component>
                                        
                                        <!-- Page numbers -->
                                        <template v-for="page in messages.links.slice(1, -1)" :key="page.label">
                                            <component 
                                                :is="page.url ? 'Link' : 'span'"
                                                :href="page.url"
                                                :class="{
                                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': page.active,
                                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0': !page.active && page.url,
                                                    'relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-400 ring-1 ring-inset ring-gray-300': !page.url
                                                }"
                                            >
                                                {{ page.label }}
                                            </component>
                                        </template>

                                        <!-- Next button -->
                                        <component 
                                            :is="messages.next_page_url ? 'Link' : 'span'"
                                            :href="messages.next_page_url"
                                            :class="{
                                                'relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0': messages.next_page_url,
                                                'relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 cursor-not-allowed': !messages.next_page_url
                                            }"
                                        >
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </component>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Message Modal -->
        <ContactMessageModal 
            :show="showMessageModal" 
            :message="selectedMessage"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>
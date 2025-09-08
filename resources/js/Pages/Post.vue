<template>
    <Layout :recentPosts="recentPosts" :categories="categories">
      <article class="bg-white rounded-lg shadow-sm overflow-hidden">
        <img :src="post.coverPhoto.url" :alt="post.title" class="w-full h-96 object-cover">
        <div class="p-8">
          <div class="flex items-center text-sm text-gray-500 mb-4">
            <span v-if="post.category" class="mr-4">{{ post?.category?.name }}</span>
            <span>{{ post.commentsCount }} comments</span>
          </div>
          <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ post.title }}</h1>
          <div class="prose max-w-none mb-8" v-html="post.content"></div>
          
          <div class="flex space-x-2 mb-8">
            <span v-for="tag in post.tags" :key="tag.id" class="text-sm bg-gray-100 text-gray-800 px-3 py-1 rounded-full">
              {{ tag.name }}
            </span>
          </div>
  
          <!-- Comments Section -->
          <div id="comments" class="border-t border-gray-200 pt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Comments</h2>

            <div v-if="post.comments.length > 0" class="space-y-6">
                <div v-for="comment in post.comments" :key="comment.id" class="border-b border-gray-200 pb-6 last:border-0">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                {{ comment.user.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-medium text-gray-900">{{ comment.user.name }}</h3>
                                <span class="text-sm text-gray-500">{{ formatDate(comment.createdAt) }}</span>
                            </div>
                            <p class="text-gray-600 mt-1">{{ comment.message }}</p>
                            
                            <!-- Replies -->
                            <div v-if="comment.replies.length > 0" class="mt-4 pl-6 border-l-2 border-gray-100 space-y-4">
                                <div v-for="reply in comment.replies" :key="reply.id" class="pt-4">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-xs">
                                                {{ reply.user.name.charAt(0).toUpperCase() }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <h3 class="text-sm font-medium text-gray-900">{{ reply.user.name }}</h3>
                                                <span class="text-sm text-gray-500">{{ formatDate(reply.createdAt) }}</span>
                                            </div>
                                            <p class="text-gray-600 mt-1">{{ reply.message }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reply Form -->
                            <div v-if="$page.props.auth.user" class="mt-4 pl-6">
                                <form @submit.prevent="submitReply(comment.id)" class="flex items-start space-x-2">
                                    <div class="flex-1">
                                        <input v-model="replyForms[comment.id]" type="text" placeholder="Write a reply..." class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                                    </div>
                                    <button type="submit" class="text-sm bg-indigo-600 text-white py-2 px-3 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Reply
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="text-gray-500">
              No comments yet. Be the first to comment!
            </div>
            
            <div v-if="$page.props.auth.user">
              <form @submit.prevent="submitComment" class="mb-8">
                <p v-if="commentError" class="text text-red-700 bg-red-200 py-2 text-center rounded">{{ commentError }}</p>
                <div class="mb-4">
                  <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                  <textarea v-model="commentForm.content" id="comment" rows="3" class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                  Post Comment
                </button>
              </form>
            </div>
            <div v-else class="mb-8">
              <p class="text-gray-600">
                Please <button @click="showLoginModal = true" class="text-indigo-600 hover:text-indigo-800">login</button> to leave a comment.
              </p>
            </div>
  
            
          </div>
        </div>
      </article>

      <!-- Login Modal -->
      <LoginModal :show="showLoginModal" @update:show="showLoginModal = $event" />
    </Layout>
  </template>
  
  <script setup>
  import { ref, nextTick } from 'vue';
  import { router } from '@inertiajs/vue3';
  import Layout from '@/Layouts/PublicLayout.vue';
  import LoginModal from '@/Components/Modals/Login.vue';
  
  const props = defineProps({
    post: Object,
    recentPosts: Array,
    categories: Array,
  });
  
  const showLoginModal = ref(false);
  const commentForm = ref({
    content: '',
  });
  
  const replyForms = ref({});
  const commentError = ref(null);
  const replyError = ref(null);
  
  const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
  };

  const scrollToComments = () => {
    nextTick(() => {
      const commentsSection = document.getElementById('comments');
      if (commentsSection) {
        commentsSection.scrollIntoView({ 
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  };
  
  const submitComment = () => {
    router.post(route('post.comment.save'), {
        message: commentForm.value.content,
        postId: props.post.id
    }, {
      onSuccess: () => {
        scrollToComments();
        commentForm.value.content = '';
      },
      onError: (error) => {
        console.log("Error:", error);
        commentError.value = error;
        setTimeout(() => commentError.value = null, 5000);
      }
    });
  };
  
  const submitReply = (commentId) => {
    router.post(route('post.comment.reply'), {
      message: replyForms.value[commentId],
      commentId: commentId
    }, {
      onSuccess: () => {
        replyForms.value[commentId] = '';
      },
      onError: (error) => {
        console.log("Error:", error);
        replyError.value = error;
        setTimeout(() => replyError.value = null, 5000);
      }
    });
  };
  </script>
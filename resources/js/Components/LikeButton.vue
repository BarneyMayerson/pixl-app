<script setup>
import HeartIcon from "@/Components/Icons/HeartIcon.vue";
import { Form } from "@inertiajs/vue3";

defineProps({
  post: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Form
    method="POST"
    :action="
      route(post.liked_by_viewer ? 'posts.unlike' : 'posts.like', [
        post.profile,
        post,
      ])
    "
  >
    <div class="flex items-center gap-1">
      <button
        aria-label="Like"
        :class="post.liked_by_viewer ? 'hover:text-pixl text-pixl' : ''"
      >
        <HeartIcon />
      </button>
      <span :class="post.liked_by_viewer ? 'hover:text-pixl text-pixl' : ''">{{
        post.likes_count
      }}</span>
    </div>
  </Form>
</template>

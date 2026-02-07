<script setup>
import RepostIcon from "@/Components/Icons/RepostIcon.vue";
import { Form } from "@inertiajs/vue3";

defineProps({
  post: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Form method="POST" :action="route('posts.repost', [post.profile, post])">
    <div class="flex items-center gap-1">
      <button
        aria-label="Re-post"
        :class="post.reposted_by_viewer ? 'hover:text-pixl text-pixl' : ''"
      >
        <RepostIcon class="h-4.25" />
      </button>
      <span :class="post.reposted_by_viewer ? 'hover:text-pixl text-pixl' : ''">
        {{ post.reposts_count }}
      </span>
    </div>
  </Form>
</template>

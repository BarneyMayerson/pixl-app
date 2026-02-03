<script setup>
import ImageIcon from "@/Components/Icons/ImageIcon.vue";
import VideoIcon from "@/Components/Icons/VideoIcon.vue";

defineProps({
  profile: {
    type: Object,
    required: true,
  },
  rows: {
    type: Number,
    required: false,
    default: null,
  },
});
</script>

<template>
  <div class="border-pixl-light/10 mt-8 flex items-start gap-4 border-b pb-4">
    <a :href="profile.show_url" class="shrink-0">
      <img
        :src="profile.avatar_url"
        :alt="`Avatar for ${profile.display_name}`"
        class="size-10 object-cover"
      />
    </a>

    <form class="grow" method="POST" action="{{ route('posts.store') }}">
      <label class="sr-only" for="content">Post body</label>
      <textarea
        class="w-full resize-none text-lg"
        name="content"
        id="content"
        :placeholder="`What's up ${profile.handle}`"
        rows="4"
      ></textarea>
      <div class="flex items-center justify-between gap-4">
        <div class="flex gap-4">
          <button type="button">
            <ImageIcon />
          </button>
          <button type="button">
            <VideoIcon />
          </button>
        </div>
        <button
          type="submit"
          class="bg-pixl hover:bg-pixl/90 active:bg-pixl/95 text-pixl-dark border border-transparent px-4 py-1 text-sm"
        >
          Post
        </button>
      </div>
    </form>
  </div>
</template>

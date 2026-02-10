<script setup>
import BackIcon from "@/Components/Icons/BackIcon.vue";
import Layout from "@/Layouts/layout.vue";
import Post from "@/Components/Post.vue";
import ProfileHeader from "@/Components/ProfileHeader.vue";

defineProps({
  profile: {
    type: Object,
    required: true,
  },
  posts: {
    type: Array,
    default: [],
  },
});
</script>

<template>
  <Layout>
    <main class="-mx-4 flex grow flex-col gap-4 overflow-y-auto px-4 py-4">
      <a :href="route('posts.index')" class="group flex items-baseline gap-1.5">
        <BackIcon />
        <span>back</span>
      </a>

      <ProfileHeader :profile />

      <ol class="border-pixl-light/10 border-t pt-4">
        <Post
          v-for="item in posts"
          :key="item.id"
          :post="item"
          :show-engagements="true"
        />
      </ol>

      <footer class="mt-30 ml-14">
        <p class="text-center">That's all, folks!</p>
        <hr class="border-pixl-light/10 my-4" />
        <!-- White noise -->
        <div class="h-20 bg-[url(/resources/images/white-noise.gif)]"></div>
      </footer>
    </main>
  </Layout>
</template>

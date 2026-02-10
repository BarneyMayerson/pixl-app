<script setup>
import Layout from "@/Layouts/layout.vue";
import Post from "@/Components/Post.vue";
import { App } from "@inertiajs/vue3";

defineProps({
  post: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Layout>
    <main class="-mx-4 flex grow flex-col gap-4 overflow-y-auto px-4 py-4">
      <div class="h-full">
        <nav class="overflow-x-auto [scrollbar-width:none]">
          <ul class="flex min-w-max justify-end gap-8 text-sm">
            <li><a href="#">For you</a></li>
            <li>
              <a class="text-pixl-light/60 hover:text-pixl-light/80" href="#"
                >Idea streams</a
              >
            </li>
            <li>
              <a class="text-pixl-light/60 hover:text-pixl-light/80" href="#"
                >Following</a
              >
            </li>
          </ul>
        </nav>
      </div>

      <!-- Feed -->
      <ol class="mt-4">
        <Post :post="post" :show-replies="true" />
      </ol>

      <AppFooter />
    </main>
  </Layout>
</template>

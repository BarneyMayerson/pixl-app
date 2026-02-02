<script setup>
import LikeButton from "@/Components/LikeButton.vue";
import ReplyButton from "@/Components/ReplyButton.vue";
import RepostButton from "@/Components/RepostButton.vue";
import SaveButton from "@/Components/SaveButton.vue";
import ShareButton from "@/Components/ShareButton.vue";
import Reply from "@/Components/Reply.vue";

defineProps({
  post: {
    type: Object,
    required: true,
  },
  showEngagement: {
    type: Boolean,
    required: false,
    default: true,
  },
  showReplies: {
    type: Boolean,
    required: false,
    default: false,
  },
});
</script>

<template>
  <li class="group/li relative flex items-start gap-4 pt-4">
    <!-- Line-through -->
    <div
      aria-hidden="true"
      class="bg-pixl-light/10 absolute left-5 top-0 h-full w-px group-last/li:h-4"
    ></div>
    <a
      href="{{ route('profiles.show', $post->profile) }}"
      class="isolate shrink-0"
    >
      <img
        :src="post.profile.avatar_url"
        :alt="`Avatar for ${post.profile.display_name}`"
        class="size-10 object-cover"
      />
    </a>
    <div class="border-pixl-light/10 grow border-b pb-5 pt-1.5">
      <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
          <p>
            <a
              class="hover:underline"
              href="{{ route('profiles.show', $post->profile) }}"
              >{{ post.profile.display_name }}</a
            >
          </p>
          <p class="text-pixl-light/40 text-xs">
            <a href="{{ route('posts.show', [$post->profile, $post]) }}">{{
              post.created_at
            }}</a>
          </p>
          <p>
            <a
              class="text-pixl-light/40 hover:text-pixl-light/60 text-xs"
              href="{{ route('profiles.show', $post->profile) }}"
              >{{ post.profile.handle }}</a
            >
          </p>
        </div>
        <button class="gap-0.75 group flex py-2" aria-label="Post options">
          <span
            class="bg-pixl-light/40 group-hover:bg-pixl-light/60 size-1"
          ></span>
          <span
            class="bg-pixl-light/40 group-hover:bg-pixl-light/60 size-1"
          ></span>
          <span
            class="bg-pixl-light/40 group-hover:bg-pixl-light/60 size-1"
          ></span>
        </button>
      </div>
      <div class="mt-4 flex flex-col gap-3 text-sm" v-html="post.content"></div>

      <div
        v-if="showEngagement"
        class="mt-6 flex items-center justify-between gap-4"
      >
        <div class="flex items-center gap-8">
          <LikeButton
            :active="post.liked_by_viewer"
            :count="post.likes_count"
            :id="post.id"
          />
          <ReplyButton :count="post.replies_count" :id="post.id" />
          <RepostButton
            :active="post.reposted_by_viewer"
            :count="post.reposts_count"
            :id="post.id"
          />
        </div>
        <div class="flex items-center gap-3">
          <SaveButton :id="post.id" />
          <ShareButton :id="post.id" />
        </div>
      </div>

      <ol v-if="showReplies">
        <Reply
          v-for="reply in post.replies"
          :key="reply.id"
          :post="reply"
          :show-engagement="showEngagement"
          :show-replies="showReplies"
        />
      </ol>
    </div>
  </li>
</template>

<script setup>
import ReplyIcon from "@/Components/Icons/ReplyIcon.vue";

defineProps({
  count: {
    type: Number,
    default: 0,
  },
});
</script>

<template>
  <div class="flex items-center gap-1">
    <button aria-label="Comment" class="hover:text-pixl">
      <ReplyIcon class="h-4.25" />
    </button>
    <span class="text-sm">{{ count }}</span>
  </div>
</template>

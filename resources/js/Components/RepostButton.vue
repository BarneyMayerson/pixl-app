<script setup>
import RepostIcon from "@/Components/Icons/RepostIcon.vue";

defineProps({
  active: {
    type: Boolean,
    default: false,
  },
  count: {
    type: Number,
    default: 0,
  },
});
</script>

<template>
  <div class="flex items-center gap-1">
    <button
      aria-label="Re-post"
      :class="active ? 'hover:text-pixl text-pixl' : ''"
    >
      <RepostIcon class="h-4.25" />
    </button>
    <span :class="active ? 'hover:text-pixl text-pixl' : ''">{{ count }}</span>
  </div>
</template>

<script setup>
import HeartIcon from "@/Components/Icons/HeartIcon.vue";

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
      aria-label="Like"
      :class="active ? 'hover:text-pixl text-pixl' : ''"
    >
      <HeartIcon />
    </button>
    <span :class="active ? 'hover:text-pixl text-pixl' : ''">{{ count }}</span>
  </div>
</template>

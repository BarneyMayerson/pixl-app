<script setup>
import Navigation from "@/Components/Navigation.vue";
import Aside from "@/Components/Aside.vue";
import FlashMessages from "@/Components/FlashMessages.vue";
</script>

<template>
  <div
    class="bg-pixl-dark text-pixl-light flex gap-8 px-4 sm:h-dvh sm:overflow-clip xl:gap-16"
  >
    <Navigation />
    <slot />
    <Aside />
    <FlashMessages timeout="10000" position="top-left" />
  </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

const page = usePage();
const flashMessage = ref("");
const show = ref(false);
const type = ref("success");

function flash(message, messageType) {
  if (!message) return;

  flashMessage.value = message;
  type.value = messageType;
  show.value = true;

  setTimeout(() => (show.value = false), 4000);
}

watch(
  () => page.props.flash.success,
  (msg) => flash(msg, "success"),
);
watch(
  () => page.props.flash.error,
  (msg) => flash(msg, "error"),
);

const bgClass = computed(() => {
  return type.value === "error" ? "bg-red-600" : "bg-green-600";
});
</script>

<template>
  <Transition
    enter-active-class="transition duration-300 transform ease-out"
    enter-from-class="translate-x-full opacity-0"
    enter-to-class="opacity-100"
    leave-active-class="transition duration-200 transform ease-out"
    leave-from-class="opacity-100"
    leave-to-class="translate-x-full opacity-0"
  >
    <div
      v-if="flashMessage && show"
      :class="[
        bgClass,
        'fixed right-4 bottom-4 rounded-md px-3 py-2 text-white',
      ]"
      v-text="flashMessage"
    />
  </Transition>
</template>

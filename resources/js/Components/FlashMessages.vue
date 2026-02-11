<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";

const page = usePage();

const toasts = ref([]);

const props = defineProps({
  timeout: {
    type: Number,
    default: 4000,
  },
  position: {
    type: String,
    default: "bottom-right",
    validator: (v) =>
      ["top-right", "top-left", "bottom-right", "bottom-left"].includes(v),
  },
});

const isLeft = computed(() => props.position.includes("left"));
const isTop = computed(() => props.position.includes("top"));

const positionClass = computed(() => {
  switch (props.position) {
    case "top-left":
      return "top-4 left-4";
    case "top-right":
      return "top-4 right-4";
    case "bottom-left":
      return "bottom-4 left-4";
    case "bottom-right":
      return "bottom-4 right-4";
    default:
      return "bottom-4 right-4";
  }
});

const enterFromTranslate = computed(() =>
  isLeft.value ? "-translate-x-8" : "translate-x-8",
);

const leaveToTranslate = computed(() =>
  isLeft.value ? "-translate-x-8" : "translate-x-8",
);

const stackDirection = computed(() =>
  isTop.value ? "flex-col-reverse" : "flex-col",
);

function addToast(message, type = "success") {
  if (!message) return;

  const id = Date.now();

  toasts.value.push({ id, message, type, visible: true });

  if (props.timeout > 0) {
    setTimeout(() => removeToast(id), props.timeout);
  }
}

function removeToast(id) {
  const toast = toasts.value.find((t) => t.id === id);

  if (toast) toast.visible = false;

  setTimeout(() => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
  }, 300);
}

watch(
  () => page.props.flash,
  (flash) => {
    if (flash?.error) addToast(flash.error, "error");
    if (flash?.success) addToast(flash.success, "success");
    if (flash?.warning) addToast(flash.warning, "warning");
    if (flash?.info) addToast(flash.info, "info");
  },
  { deep: true },
);

const bgClass = (type) => {
  const map = {
    success: "bg-emerald-600",
    error: "bg-red-600",
    warning: "bg-amber-600",
    info: "bg-blue-600",
  };
  return map[type] || "bg-emerald-600";
};
</script>

<template>
  <Teleport to="body">
    <div :class="[positionClass, stackDirection, 'fixed z-50 flex gap-2']">
      <TransitionGroup
        :enter-active-class="`transition-all duration-300 ease-out`"
        :enter-from-class="`opacity-0 scale-95 ${enterFromTranslate}`"
        :enter-to-class="`opacity-100 scale-100 translate-x-0`"
        :leave-active-class="`transition-all duration-200 ease-out`"
        :leave-from-class="`opacity-100 scale-100 translate-x-0`"
        :leave-to-class="`opacity-0 scale-95 ${leaveToTranslate}`"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          v-show="toast.visible"
          :class="[
            bgClass(toast.type),
            'max-w-105 min-w-70 rounded-xl px-4 py-3 text-white shadow-xl',
            'group flex items-start gap-3',
          ]"
          role="alert"
          aria-live="polite"
        >
          <div class="flex-1 pt-0.5 text-sm leading-snug">
            {{ toast.message }}
          </div>
          <button
            @click="removeToast(toast.id)"
            class="-mt-1 -mr-1 rounded-lg p-1 opacity-60 transition-opacity hover:bg-white/20 hover:opacity-100"
            aria-label="Close notification"
          >
            ✕
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

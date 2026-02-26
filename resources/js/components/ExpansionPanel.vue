<template>
  <div class="rounded-lg bg-white p-3">
    <div class="flex shrink-0 text-left items-center pointer" @click="formExpandido = !formExpandido">
      <svg class="w-3 h-3 shrink-0 mx-2 fill-current text-gray-400 dark:text-gray-500" :class="formExpandido && 'rotate-180'" viewBox="0 0 12 12">
        <path d="M5.9 11.4L.5 6l1.4-1.4 4 4 4-4L11.3 6z" />
      </svg>
      <p :class="['text-gray-500', {'font-semibold': formExpandido}]">
        {{titulo}}
      </p>
    </div>
    <div :class="[{'hidden':!formExpandido}]">
      <slot />
    </div>
  </div>
</template>

<script>

export default {
  data() {
    return {
      formExpandido: false
    };
  },
  props: {
    titulo: {
      default: null
    }
  },
  mounted() {
  },
  methods: {
  },
  watch: {
  }
};
</script>